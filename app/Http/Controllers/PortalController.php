<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use App\Models\ComentarioPortal;
use App\Models\ComentarioPropiedad;
use App\Models\Favorito;
use App\Models\ImagenPropiedad;
use App\Models\PortalVisita;
use App\Models\Propiedad;
use App\Models\ResenaPropiedad;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use App\Models\Visita;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function comoPublicar(): View
    {
        $comentariosPortal = ComentarioPortal::query()
            ->where('visible', true)
            ->with('usuario:id,name,apellidos')
            ->latest()
            ->limit(10)
            ->get();

        $metricasPortal = ComentarioPortal::query()
            ->where('visible', true)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('AVG(puntaje) as promedio')
            ->first();

        $totalComentariosPortal = (int) ($metricasPortal?->total ?? 0);
        $promedioPuntajePortal = $metricasPortal?->promedio;

        return view('portal.como-publicar', [
            'comentariosPortal' => $comentariosPortal,
            'promedioPuntajePortal' => $promedioPuntajePortal !== null
                ? number_format((float) $promedioPuntajePortal, 1, '.', ',')
                : null,
            'totalComentariosPortal' => $totalComentariosPortal,
        ]);
    }

    public function storeComentarioComoPublicar(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('portalFeedback', [
            'puntaje' => ['required', 'integer', 'between:1,5'],
            'comentario' => ['required', 'string', 'min:10', 'max:1000'],
            'sugerencia' => ['nullable', 'string', 'max:1000'],
        ]);

        $feedback = ComentarioPortal::query()->firstOrNew([
            'user_id' => $request->user()->id,
        ]);

        $feedback->puntaje = (int) $validated['puntaje'];
        $feedback->comentario = trim($validated['comentario']);
        $feedback->sugerencia = isset($validated['sugerencia'])
            ? trim((string) $validated['sugerencia'])
            : null;
        if (! $feedback->exists) {
            $feedback->visible = true;
        }
        $feedback->save();

        $mensaje = $feedback->wasRecentlyCreated
            ? 'Gracias por tu reseña. Ya guardamos tu comentario.'
            : 'Actualizamos tu reseña y sugerencia correctamente.';

        return back()->with('portal_feedback_success', $mensaje);
    }

    public function index(Request $request): View
    {
        $this->registerPortalVisit($request);

        $tiposPropiedad = Cache::remember(
            'portal:tipos-propiedad:v1',
            now()->addMinutes(30),
            static fn () => TipoPropiedad::query()
                ->orderBy('nombre')
                ->get(['id', 'nombre'])
        );

        $tiposProyectoIds = $tiposPropiedad
            ->filter(function (TipoPropiedad $tipo): bool {
                return str_contains(Str::lower($tipo->nombre), 'proyecto');
            })
            ->pluck('id')
            ->map(static fn ($id): int => (int) $id)
            ->values()
            ->all();

        $modo = $request->string('modo')->toString();
        $soloFavoritos = $request->boolean('favoritos') && $request->user() !== null;
        if (! in_array($modo, ['comprar', 'alquilar', 'proyectos'], true)) {
            if ($request->string('operacion')->toString() === 'venta') {
                $modo = 'comprar';
            } elseif ($request->string('operacion')->toString() === 'alquiler') {
                $modo = 'alquilar';
            } elseif (in_array($request->integer('tipo_propiedad_id'), $tiposProyectoIds, true)) {
                $modo = 'proyectos';
            } elseif ($soloFavoritos) {
                $modo = '';
            } else {
                $modo = 'comprar';
            }
        }

        $filtros = [
            'modo' => $modo,
            'operacion' => in_array($request->string('operacion')->toString(), ['venta', 'alquiler'], true)
                ? $request->string('operacion')->toString()
                : '',
            'tipo_propiedad_id' => $request->integer('tipo_propiedad_id') > 0
                ? $request->integer('tipo_propiedad_id')
                : null,
            'tipo_proyecto_ids' => $tiposProyectoIds,
            'ubicacion' => trim($request->string('ubicacion')->toString()),
            'ciudad' => trim($request->string('ciudad')->toString()),
            'precio_min' => $this->numericOrNull($request->input('precio_min')),
            'precio_max' => $this->numericOrNull($request->input('precio_max')),
            'area_min' => $this->numericOrNull($request->input('area_min')),
            'dormitorios' => $request->integer('dormitorios') > 0
                ? $request->integer('dormitorios')
                : null,
            'solo_favoritos' => $soloFavoritos,
            'orden' => in_array($request->string('orden')->toString(), ['recientes', 'precio_asc', 'precio_desc'], true)
                ? $request->string('orden')->toString()
                : 'recientes',
        ];

        if ($filtros['tipo_propiedad_id'] !== null && ! $tiposPropiedad->contains('id', $filtros['tipo_propiedad_id'])) {
            $filtros['tipo_propiedad_id'] = null;
        }

        if ($filtros['modo'] === 'proyectos' && $filtros['tipo_propiedad_id'] !== null && ! in_array($filtros['tipo_propiedad_id'], $tiposProyectoIds, true)) {
            $filtros['tipo_propiedad_id'] = null;
        }

        $query = Propiedad::query()
            ->where('estado', 'disponible')
            ->with([
                'usuario:id,name,apellidos',
                'usuario.verificacionUsuario:id,user_id,estado,dni_legible,datos_coinciden,contacto_validado',
                'tipoPropiedad:id,nombre',
                'ubicacion:id,departamento,provincia,distrito',
                'portadaImagen',
            ])
            ->withCount(['imagenes', 'contactos', 'favoritos', 'visitas', 'comentarios']);
        $this->applyUsuarioVerificadoSelect($query);

        $this->applyFilters($query, $filtros);

        if ($filtros['solo_favoritos'] && $request->user() !== null) {
            $query->whereHas('favoritos', function ($favoritosQuery) use ($request): void {
                $favoritosQuery->where('user_id', $request->user()->id);
            });
        }

        $destacadas = (clone $query)
            ->orderByDesc('verificada_por_kusay')
            ->orderByDesc('visitas_count')
            ->orderByDesc('contactos_count')
            ->latest()
            ->limit(3)
            ->get();

        $totalResultados = (clone $query)->count();

        $query->orderByDesc('verificada_por_kusay');
        match ($filtros['orden']) {
            'precio_asc' => $query->orderBy('precio'),
            'precio_desc' => $query->orderByDesc('precio'),
            default => $query->latest(),
        };

        $propiedades = $query->paginate(9)->withQueryString();

        $filtrosActivos = $filtros['operacion'] !== ''
            || $filtros['tipo_propiedad_id'] !== null
            || $filtros['ubicacion'] !== ''
            || $filtros['ciudad'] !== ''
            || $filtros['precio_min'] !== null
            || $filtros['precio_max'] !== null
            || $filtros['area_min'] !== null
            || $filtros['dormitorios'] !== null
            || $filtros['orden'] !== 'recientes';

        $mostrarBloquesPrincipal = ! $filtros['solo_favoritos']
            && $filtros['modo'] === 'comprar'
            && ! $filtrosActivos;

        $bloquesPrincipal = [
            'venta' => collect(),
            'alquiler' => collect(),
            'proyectos' => collect(),
        ];

        if ($mostrarBloquesPrincipal) {
            $queryBloques = Propiedad::query()
                ->where('estado', 'disponible')
                ->with([
                    'usuario:id,name,apellidos',
                    'usuario.verificacionUsuario:id,user_id,estado,dni_legible,datos_coinciden,contacto_validado',
                    'tipoPropiedad:id,nombre',
                    'ubicacion:id,departamento,provincia,distrito',
                    'portadaImagen',
                ])
                ->withCount(['imagenes', 'contactos', 'favoritos', 'visitas', 'comentarios'])
                ->orderByDesc('verificada_por_kusay')
                ->latest();
            $this->applyUsuarioVerificadoSelect($queryBloques);

            $bloqueVentaQuery = (clone $queryBloques)
                ->where('tipo', 'venta');
            if ($tiposProyectoIds !== []) {
                $bloqueVentaQuery->whereNotIn('tipo_propiedad_id', $tiposProyectoIds);
            }
            $bloquesPrincipal['venta'] = $bloqueVentaQuery
                ->limit(6)
                ->get();

            $bloqueAlquilerQuery = (clone $queryBloques)
                ->where('tipo', 'alquiler');
            if ($tiposProyectoIds !== []) {
                $bloqueAlquilerQuery->whereNotIn('tipo_propiedad_id', $tiposProyectoIds);
            }
            $bloquesPrincipal['alquiler'] = $bloqueAlquilerQuery
                ->limit(6)
                ->get();

            $bloquesPrincipal['proyectos'] = $tiposProyectoIds === []
                ? collect()
                : (clone $queryBloques)
                    ->whereIn('tipo_propiedad_id', $tiposProyectoIds)
                    ->limit(6)
                    ->get();
        }

        $ciudadesTop = Cache::remember(
            'portal:ciudades-top:v1',
            now()->addMinutes(10),
            static fn () => Ubicacion::query()
                ->select('ubicaciones.distrito', 'ubicaciones.departamento')
                ->selectRaw('COUNT(propiedades.id) as propiedades_count')
                ->join('propiedades', 'propiedades.ubicacion_id', '=', 'ubicaciones.id')
                ->where('propiedades.estado', 'disponible')
                ->groupBy('ubicaciones.distrito', 'ubicaciones.departamento')
                ->orderByDesc('propiedades_count')
                ->limit(8)
                ->get()
        );

        $propiedadesVisibles = $destacadas
            ->pluck('id')
            ->merge($propiedades->getCollection()->pluck('id'))
            ->unique()
            ->values();

        if ($mostrarBloquesPrincipal) {
            $propiedadesVisibles = $propiedadesVisibles
                ->merge($bloquesPrincipal['venta']->pluck('id'))
                ->merge($bloquesPrincipal['alquiler']->pluck('id'))
                ->merge($bloquesPrincipal['proyectos']->pluck('id'))
                ->unique()
                ->values();
        }

        $favoritasIds = collect();
        if ($request->user() !== null && $propiedadesVisibles->isNotEmpty()) {
            $favoritasIds = Favorito::query()
                ->where('user_id', $request->user()->id)
                ->whereIn('propiedad_id', $propiedadesVisibles)
                ->pluck('propiedad_id');
        }

        return view('portal.index', [
            'tiposPropiedad' => $tiposPropiedad,
            'filtros' => $filtros,
            'totalResultados' => $totalResultados,
            'propiedades' => $propiedades,
            'destacadas' => $destacadas,
            'ciudadesTop' => $ciudadesTop,
            'favoritasIds' => $favoritasIds,
            'mostrarBloquesPrincipal' => $mostrarBloquesPrincipal,
            'bloquesPrincipal' => $bloquesPrincipal,
        ]);
    }

    public function show(Request $request, Propiedad $propiedad): View
    {
        if ($propiedad->estado !== 'disponible') {
            abort(404);
        }

        $this->registerPropertyClick($request, $propiedad);

        $propiedad->loadMissing([
            'usuario:id,name,apellidos,email,telefono,whatsapp',
            'usuario.verificacionUsuario:id,user_id,estado,dni_legible,datos_coinciden,contacto_validado',
            'tipoPropiedad:id,nombre',
            'ubicacion:id,departamento,provincia,distrito',
            'imagenes:id,propiedad_id,ruta_imagen',
            'portadaImagen',
            'comentarios' => function ($comentariosQuery): void {
                $comentariosQuery
                    ->select('id', 'propiedad_id', 'user_id', 'puntaje', 'mensaje', 'created_at')
                    ->latest()
                    ->limit(40);
            },
            'comentarios.usuario:id,name,apellidos',
            'resenas' => function ($resenasQuery): void {
                $resenasQuery
                    ->select('id', 'propiedad_id', 'user_id', 'puntaje', 'comentario', 'created_at')
                    ->latest()
                    ->limit(40);
            },
            'resenas.usuario:id,name,apellidos',
        ]);
        $propiedad->loadCount(['imagenes', 'contactos', 'favoritos', 'visitas', 'comentarios', 'resenas']);

        $esFavorita = false;
        if ($request->user() !== null) {
            $esFavorita = Favorito::query()
                ->where('user_id', $request->user()->id)
                ->where('propiedad_id', $propiedad->id)
                ->exists();
        }

        return view('portal.show', [
            'propiedad' => $propiedad,
            'esFavorita' => $esFavorita,
        ]);
    }

    public function storeComentario(Request $request, Propiedad $propiedad): RedirectResponse
    {
        if ($propiedad->estado !== 'disponible') {
            abort(404);
        }

        $validated = $request->validateWithBag('comentario', [
            'puntaje' => ['required', 'integer', 'between:1,5'],
            'mensaje' => ['required', 'string', 'min:4', 'max:700'],
        ]);

        ComentarioPropiedad::query()->create([
            'propiedad_id' => $propiedad->id,
            'user_id' => $request->user()->id,
            'puntaje' => (int) $validated['puntaje'],
            'mensaje' => trim($validated['mensaje']),
        ]);

        return back()->with('comentario_success', 'Tu comentario fue publicado correctamente.');
    }

    public function storeResena(Request $request, Propiedad $propiedad): RedirectResponse
    {
        if ($propiedad->estado !== 'disponible') {
            abort(404);
        }

        $validated = $request->validateWithBag('resena', [
            'puntaje' => ['required', 'integer', 'between:1,5'],
            'comentario' => ['nullable', 'string', 'max:700'],
        ]);

        $resena = ResenaPropiedad::query()->updateOrCreate(
            [
                'propiedad_id' => $propiedad->id,
                'user_id' => $request->user()->id,
            ],
            [
                'puntaje' => (int) $validated['puntaje'],
                'comentario' => isset($validated['comentario']) ? trim((string) $validated['comentario']) : null,
            ]
        );

        $mensaje = $resena->wasRecentlyCreated
            ? 'Tu reseña fue publicada correctamente.'
            : 'Tu reseña fue actualizada correctamente.';

        return back()->with('resena_success', $mensaje);
    }

    public function registrarClic(Request $request, Propiedad $propiedad): JsonResponse
    {
        if ($propiedad->estado !== 'disponible') {
            abort(404);
        }

        $this->registerPropertyClick($request, $propiedad);

        return response()->json([
            'ok' => true,
            'total_clics' => $propiedad->visitas()->count(),
        ]);
    }

    public function toggleFavorito(Request $request, Propiedad $propiedad): JsonResponse
    {
        if ($propiedad->estado !== 'disponible') {
            abort(404);
        }

        $user = $request->user();
        if ($user === null) {
            return response()->json([
                'ok' => false,
                'message' => 'Debes iniciar sesion para guardar favoritos.',
            ], 401);
        }

        if (! $user->hasVerifiedEmail()) {
            return response()->json([
                'ok' => false,
                'message' => 'Debes verificar tu correo para guardar favoritos.',
                'redirect' => route('verification.notice'),
            ], 403);
        }

        $favorito = Favorito::query()
            ->where('user_id', $user->id)
            ->where('propiedad_id', $propiedad->id)
            ->first();

        if ($favorito !== null) {
            $favorito->delete();
            $favorita = false;
        } else {
            try {
                Favorito::query()->firstOrCreate([
                    'user_id' => $user->id,
                    'propiedad_id' => $propiedad->id,
                ]);
            } catch (QueryException $exception) {
                if (! $this->isDuplicateKeyException($exception)) {
                    throw $exception;
                }
            }
            $favorita = true;
        }

        $totalFavoritos = $propiedad->favoritos()->count();

        return response()->json([
            'ok' => true,
            'favorita' => $favorita,
            'total_favoritos' => $totalFavoritos,
            'message' => $favorita
                ? 'Propiedad agregada a tus favoritos.'
                : 'Propiedad eliminada de tus favoritos.',
        ]);
    }

    public function solicitarContacto(Request $request, Propiedad $propiedad): RedirectResponse
    {
        if ($propiedad->estado !== 'disponible') {
            abort(404);
        }

        $usuarioSolicitante = $request->user();
        if ($usuarioSolicitante === null) {
            return redirect()->route('login');
        }

        $propiedad->loadMissing([
            'usuario:id,name,apellidos,email,solicitudes_vistas_at',
            'ubicacion:id,departamento,provincia,distrito',
        ]);

        $validated = $request->validateWithBag('contacto', [
            'nombre' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'mensaje' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        try {
            $contacto = Contacto::query()->firstOrCreate(
                [
                    'propiedad_id' => $propiedad->id,
                    'user_id' => $usuarioSolicitante->id,
                ],
                [
                    'nombre' => trim($validated['nombre']),
                    'email' => trim($validated['email']),
                    'telefono' => isset($validated['telefono']) ? trim((string) $validated['telefono']) : null,
                    'mensaje' => trim($validated['mensaje']),
                ]
            );
        } catch (QueryException $exception) {
            if ($this->isDuplicateKeyException($exception)) {
                return back()->with('contacto_info', 'Ya enviaste una solicitud para esta propiedad. El anunciante te responderá pronto.');
            }

            throw $exception;
        }

        if (! $contacto->wasRecentlyCreated) {
            return back()->with('contacto_info', 'Ya enviaste una solicitud para esta propiedad. El anunciante te responderá pronto.');
        }

        $propiedad->usuario?->forgetUnreadSolicitudesCache();

        $correoDestino = $propiedad->usuario?->email;
        if ($correoDestino !== null && $correoDestino !== '') {
            dispatch(function () use ($correoDestino, $propiedad, $contacto): void {
                try {
                    Mail::raw($this->buildContactEmailBody($propiedad, $contacto), function (Message $message) use ($correoDestino, $propiedad, $contacto): void {
                        $message
                            ->to($correoDestino)
                            ->replyTo($contacto->email, $contacto->nombre)
                            ->subject('Nueva solicitud por tu propiedad: '.$propiedad->titulo);
                    });
                } catch (\Throwable $exception) {
                    Log::warning('No se pudo enviar correo de solicitud de contacto.', [
                        'propiedad_id' => $propiedad->id,
                        'contacto_id' => $contacto->id,
                        'error' => $exception->getMessage(),
                    ]);
                }
            })->afterResponse();
        }

        return back()->with('contacto_success', 'Tu solicitud fue enviada al anunciante. Te contactará con los datos que registraste.');
    }

    public function imagen(Request $request, Propiedad $propiedad, ImagenPropiedad $imagen)
    {
        if ($propiedad->estado !== 'disponible') {
            abort(404);
        }

        if ($imagen->propiedad_id !== $propiedad->id) {
            abort(404);
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($imagen->ruta_imagen)) {
            abort(404);
        }

        $path = $disk->path($imagen->ruta_imagen);
        $lastModified = @filemtime($path) ?: time();
        $size = @filesize($path) ?: 0;
        $etag = '"'.sha1($imagen->id.'|'.$lastModified.'|'.$size).'"';
        $ifNoneMatch = trim((string) $request->headers->get('If-None-Match'));

        $headers = [
            'Cache-Control' => 'public, max-age=604800, stale-while-revalidate=86400',
            'ETag' => $etag,
            'Last-Modified' => gmdate('D, d M Y H:i:s', $lastModified).' GMT',
            'X-Content-Type-Options' => 'nosniff',
        ];

        if ($ifNoneMatch === $etag) {
            return response('', 304, $headers);
        }

        return response()->file($path, $headers);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<Propiedad>  $query
     * @param  array{
     *     modo: string,
     *     operacion: string,
     *     tipo_propiedad_id: int|null,
     *     tipo_proyecto_ids: array<int>,
     *     ubicacion: string,
     *     ciudad: string,
     *     precio_min: float|null,
     *     precio_max: float|null,
     *     area_min: float|null,
     *     dormitorios: int|null,
     *     solo_favoritos: bool,
     *     orden: string
     * }  $filtros
     */
    private function applyFilters($query, array $filtros): void
    {
        if ($filtros['modo'] === 'comprar') {
            $query->where('tipo', 'venta');
            if ($filtros['tipo_proyecto_ids'] !== []) {
                $query->whereNotIn('tipo_propiedad_id', $filtros['tipo_proyecto_ids']);
            }
        } elseif ($filtros['modo'] === 'alquilar') {
            $query->where('tipo', 'alquiler');
            if ($filtros['tipo_proyecto_ids'] !== []) {
                $query->whereNotIn('tipo_propiedad_id', $filtros['tipo_proyecto_ids']);
            }
        } elseif ($filtros['modo'] === 'proyectos') {
            if ($filtros['tipo_proyecto_ids'] === []) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('tipo_propiedad_id', $filtros['tipo_proyecto_ids']);
            }
        } elseif ($filtros['operacion'] !== '') {
            $query->where('tipo', $filtros['operacion']);
        }

        if ($filtros['tipo_propiedad_id'] !== null) {
            $query->where('tipo_propiedad_id', $filtros['tipo_propiedad_id']);
        }

        if ($filtros['ciudad'] !== '') {
            $query->whereHas('ubicacion', function ($ubicacionQuery) use ($filtros): void {
                $ubicacionQuery->where('distrito', $filtros['ciudad']);
            });
        }

        if ($filtros['ubicacion'] !== '') {
            $term = '%'.$filtros['ubicacion'].'%';

            $query->where(function ($searchQuery) use ($term): void {
                $searchQuery
                    ->where('titulo', 'like', $term)
                    ->orWhere('direccion', 'like', $term)
                    ->orWhere('descripcion', 'like', $term)
                    ->orWhereHas('ubicacion', function ($ubicacionQuery) use ($term): void {
                        $ubicacionQuery
                            ->where('departamento', 'like', $term)
                            ->orWhere('provincia', 'like', $term)
                            ->orWhere('distrito', 'like', $term);
                    });
            });
        }

        if ($filtros['precio_min'] !== null) {
            $query->where('precio', '>=', $filtros['precio_min']);
        }

        if ($filtros['precio_max'] !== null) {
            $query->where('precio', '<=', $filtros['precio_max']);
        }

        if ($filtros['area_min'] !== null) {
            $query->where('area', '>=', $filtros['area_min']);
        }

        if ($filtros['dormitorios'] !== null) {
            $query->where('habitaciones', '>=', $filtros['dormitorios']);
        }
    }

    private function numericOrNull(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (! is_numeric($value)) {
            return null;
        }

        $number = (float) $value;

        return $number >= 0 ? $number : null;
    }

    private function registerPortalVisit(Request $request): void
    {
        $visitorKey = $this->resolvePortalVisitorKey($request);
        $today = now()->toDateString();
        $cacheKey = 'portal:visit:'.$visitorKey.':'.$today;

        // Evita doble escritura bajo alta concurrencia (operacion atomica en cache).
        if (! Cache::add($cacheKey, true, now()->endOfDay())) {
            return;
        }

        PortalVisita::create([
            'user_id' => $request->user()?->id,
            'visitor_key' => $visitorKey,
            'session_id' => $request->session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'fecha_visita' => now(),
        ]);
    }

    private function resolvePortalVisitorKey(Request $request): string
    {
        if ($request->user() !== null) {
            return 'user:'.$request->user()->id;
        }

        $sessionVisitorKey = $request->session()->get('portal_visitor_key');
        if (! is_string($sessionVisitorKey) || $sessionVisitorKey === '') {
            $sessionVisitorKey = (string) Str::uuid();
            $request->session()->put('portal_visitor_key', $sessionVisitorKey);
        }

        return 'session:'.$sessionVisitorKey;
    }

    private function registerPropertyClick(Request $request, Propiedad $propiedad): void
    {
        $visitorKey = $this->resolvePortalVisitorKey($request);
        $bucketMinutes = (int) floor(now()->minute / 15) * 15;
        $timeBucket = now()->format('YmdH').str_pad((string) $bucketMinutes, 2, '0', STR_PAD_LEFT);
        $cacheKey = 'portal:click:'.$propiedad->id.':'.$visitorKey.':'.$timeBucket;

        // Limita rafagas repetidas de la misma sesion/usuario y reduce carga de escritura.
        if (! Cache::add($cacheKey, true, now()->addMinutes(16))) {
            return;
        }

        Visita::create([
            'propiedad_id' => $propiedad->id,
            'user_id' => $request->user()?->id,
            'fecha_visita' => now(),
        ]);
    }

    private function buildContactEmailBody(Propiedad $propiedad, Contacto $contacto): string
    {
        $ubicacion = collect([
            $propiedad->ubicacion?->distrito,
            $propiedad->ubicacion?->provincia,
            $propiedad->ubicacion?->departamento,
        ])->filter()->implode(', ');

        return implode(PHP_EOL, [
            'Nueva solicitud de contacto en Kusay.pe',
            '',
            'Propiedad: '.$propiedad->titulo,
            'Ubicación: '.($ubicacion !== '' ? $ubicacion : 'No especificada'),
            'Precio: S/ '.number_format((float) $propiedad->precio, 2, '.', ','),
            '',
            'Datos del interesado:',
            'Nombre: '.$contacto->nombre,
            'Correo: '.$contacto->email,
            'Teléfono: '.($contacto->telefono ?: 'No indicado'),
            '',
            'Mensaje:',
            $contacto->mensaje,
            '',
            'Responde directamente a este correo para contactar al interesado.',
        ]);
    }
    private function resolveTipoIcon(string $tipoNombre): string
    {
        $nombre = Str::lower($tipoNombre);

        return match (true) {
            str_contains($nombre, 'terreno') => 'bi bi-tree-fill',
            str_contains($nombre, 'casa') => 'bi bi-house-door-fill',
            str_contains($nombre, 'depart') => 'bi bi-building',
            str_contains($nombre, 'lote') => 'bi bi-triangle-fill',
            str_contains($nombre, 'local') => 'bi bi-shop',
            str_contains($nombre, 'chacra'), str_contains($nombre, 'finca') => 'bi bi-flower1',
            str_contains($nombre, 'oficina') => 'bi bi-briefcase-fill',
            str_contains($nombre, 'proyecto') => 'bi bi-map-fill',
            default => 'bi bi-tag-fill',
        };
    }

    private function isDuplicateKeyException(QueryException $exception): bool
    {
        return $exception->getCode() === '23000'
            || $exception->getCode() === '19';
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder<Propiedad> $query
     */
    private function applyUsuarioVerificadoSelect($query): void
    {
        $query
            ->addSelect('propiedades.*')
            ->selectRaw(
                "EXISTS(
                    SELECT 1
                    FROM verificaciones_usuario vu
                    WHERE vu.user_id = propiedades.user_id
                      AND vu.estado = 'aprobado'
                      AND vu.dni_legible = 1
                      AND vu.datos_coinciden = 1
                      AND vu.contacto_validado = 1
                ) as verificada_por_kusay"
            );
    }
}
