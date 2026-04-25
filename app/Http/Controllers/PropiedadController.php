<?php

namespace App\Http\Controllers;

use App\Models\ImagenPropiedad;
use App\Models\Propiedad;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PropiedadController extends Controller
{
    public function misPublicaciones(Request $request): View
    {
        $estado = $request->string('estado')->toString();
        $tipoOperacion = $request->string('tipo')->toString();
        $orden = $request->string('orden')->toString();

        $estadosValidos = ['disponible', 'reservado', 'vendido'];
        $tiposValidos = ['venta', 'alquiler'];
        $ordenesValidas = ['recientes', 'antiguas', 'precio_asc', 'precio_desc'];

        if (! in_array($orden, $ordenesValidas, true)) {
            $orden = 'recientes';
        }

        $query = Propiedad::query()
            ->where('user_id', $request->user()->id)
            ->with([
                'tipoPropiedad:id,nombre',
                'ubicacion:id,departamento,provincia,distrito',
                'imagenes:id,propiedad_id,ruta_imagen',
            ])
            ->withCount(['imagenes', 'contactos', 'favoritos', 'visitas']);

        if (in_array($estado, $estadosValidos, true)) {
            $query->where('estado', $estado);
        }

        if (in_array($tipoOperacion, $tiposValidos, true)) {
            $query->where('tipo', $tipoOperacion);
        }

        match ($orden) {
            'antiguas' => $query->oldest(),
            'precio_asc' => $query->orderBy('precio'),
            'precio_desc' => $query->orderByDesc('precio'),
            default => $query->latest(),
        };

        $propiedades = $query->paginate(9)->withQueryString();

        $estadisticas = [
            'total' => $request->user()->propiedades()->count(),
            'disponibles' => $request->user()->propiedades()->where('estado', 'disponible')->count(),
            'reservadas' => $request->user()->propiedades()->where('estado', 'reservado')->count(),
            'vendidas' => $request->user()->propiedades()->where('estado', 'vendido')->count(),
        ];

        return view('propiedades.mine', [
            'propiedades' => $propiedades,
            'estadisticas' => $estadisticas,
            'filtros' => [
                'estado' => $estado,
                'tipo' => $tipoOperacion,
                'orden' => $orden,
            ],
        ]);
    }

    public function create(Request $request): View
    {
        $fotosTemporales = array_values($request->session()->get('propiedades.temp_fotos', []));

        return view('propiedades.create', [
            'fotosTemporales' => $fotosTemporales,
            'fotosCount' => count($fotosTemporales),
        ]);
    }

    public function storeFotos(Request $request): RedirectResponse
    {
        $request->validate([
            'fotos' => ['required', 'array', 'min:1'],
            'fotos.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], $this->fotosMessages(), $this->fotosAttributes());

        $fotosTemporales = array_values($request->session()->get('propiedades.temp_fotos', []));
        $nuevasFotos = $request->file('fotos', []);

        if (count($fotosTemporales) + count($nuevasFotos) > 12) {
            return back()->withErrors([
                'fotos' => 'Puedes subir como maximo 12 fotos en total.',
            ]);
        }

        foreach ($nuevasFotos as $foto) {
            $fotosTemporales[] = $foto->store('tmp/propiedades/'.$request->user()->id, 'public');
        }

        $request->session()->put('propiedades.temp_fotos', $fotosTemporales);

        return back()->with('success', 'Fotos guardadas temporalmente. Puedes continuar al paso de datos.');
    }

    public function removeFotoTemporal(Request $request, int $index): RedirectResponse
    {
        $fotosTemporales = array_values($request->session()->get('propiedades.temp_fotos', []));

        if (! isset($fotosTemporales[$index])) {
            return back();
        }

        $ruta = $fotosTemporales[$index];
        if (Storage::disk('public')->exists($ruta)) {
            Storage::disk('public')->delete($ruta);
        }

        unset($fotosTemporales[$index]);
        $request->session()->put('propiedades.temp_fotos', array_values($fotosTemporales));

        return back()->with('success', 'Foto quitada correctamente.');
    }

    public function tempFoto(Request $request, int $index)
    {
        $fotosTemporales = array_values($request->session()->get('propiedades.temp_fotos', []));

        if (! isset($fotosTemporales[$index])) {
            abort(404);
        }

        $ruta = $fotosTemporales[$index];
        if (! Storage::disk('public')->exists($ruta)) {
            abort(404);
        }

        return Storage::disk('public')->response($ruta);
    }

    public function createDatos(Request $request): View
    {
        $tiposPropiedad = TipoPropiedad::query()
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        $fotosTemporales = array_values($request->session()->get('propiedades.temp_fotos', []));

        return view('propiedades.datos', [
            'tiposPropiedad' => $tiposPropiedad,
            'puedeGuardar' => $tiposPropiedad->isNotEmpty() && count($fotosTemporales) > 0,
            'fotosTemporales' => $fotosTemporales,
            'fotosCount' => count($fotosTemporales),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $fotosTemporales = array_values($request->session()->get('propiedades.temp_fotos', []));

        if (count($fotosTemporales) === 0) {
            return redirect()
                ->route('propiedades.create')
                ->withErrors(['fotos' => 'Debes subir al menos una foto antes de continuar.']);
        }

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string', 'min:20'],
            'precio' => ['required', 'numeric', 'min:0'],
            'tipo' => ['required', 'in:venta,alquiler'],
            'estado' => ['required', 'in:disponible,vendido,reservado'],
            'direccion' => ['required', 'string', 'max:255'],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
            'habitaciones' => ['nullable', 'integer', 'min:0'],
            'banos' => ['nullable', 'integer', 'min:0'],
            'area' => ['nullable', 'numeric', 'min:0'],
            'tipo_propiedad_id' => ['required', 'exists:tipos_propiedad,id'],
            'departamento' => ['required', 'string', 'max:120'],
            'provincia' => ['required', 'string', 'max:120'],
            'distrito' => ['required', 'string', 'max:120'],
        ], $this->datosMessages(), $this->datosAttributes());

        [$propiedad, $fotosCount] = DB::transaction(function () use ($validated, $request, $fotosTemporales) {
            $ubicacion = Ubicacion::firstOrCreate([
                'departamento' => trim($validated['departamento']),
                'provincia' => trim($validated['provincia']),
                'distrito' => trim($validated['distrito']),
            ]);

            $propiedad = Propiedad::create([
                'titulo' => $validated['titulo'],
                'descripcion' => $validated['descripcion'],
                'precio' => $validated['precio'],
                'tipo' => $validated['tipo'],
                'estado' => $validated['estado'],
                'direccion' => $validated['direccion'],
                'latitud' => $validated['latitud'] ?? null,
                'longitud' => $validated['longitud'] ?? null,
                'habitaciones' => $validated['habitaciones'] ?? null,
                'banos' => $validated['banos'] ?? null,
                'area' => $validated['area'] ?? null,
                'user_id' => $request->user()->id,
                'tipo_propiedad_id' => $validated['tipo_propiedad_id'],
                'ubicacion_id' => $ubicacion->id,
            ]);

            $fotosCount = 0;

            foreach ($fotosTemporales as $rutaTemporal) {
                if (! Storage::disk('public')->exists($rutaTemporal)) {
                    continue;
                }

                $extension = pathinfo($rutaTemporal, PATHINFO_EXTENSION) ?: 'jpg';
                $rutaFinal = 'propiedades/'.Str::uuid().'.'.$extension;

                Storage::disk('public')->move($rutaTemporal, $rutaFinal);

                ImagenPropiedad::create([
                    'ruta_imagen' => $rutaFinal,
                    'propiedad_id' => $propiedad->id,
                ]);

                $fotosCount++;
            }

            return [$propiedad, $fotosCount];
        });

        $request->session()->forget('propiedades.temp_fotos');
        Storage::disk('public')->deleteDirectory('tmp/propiedades/'.$request->user()->id);

        return redirect()
            ->route('propiedades.publicada', $propiedad)
            ->with('fotos_count', $fotosCount);
    }

    public function publicada(Request $request, Propiedad $propiedad): View
    {
        $this->ensureCanManagePropiedad($request, $propiedad);

        return view('propiedades.publicada', [
            'propiedad' => $propiedad,
            'fotosCount' => session('fotos_count', $propiedad->imagenes()->count()),
        ]);
    }

    public function actualizarEstado(Request $request, Propiedad $propiedad): RedirectResponse
    {
        $this->ensureCanManagePropiedad($request, $propiedad);

        $validated = $request->validate([
            'estado' => ['required', 'in:disponible,reservado,vendido'],
        ], [
            'estado.required' => 'Debes seleccionar un estado.',
            'estado.in' => 'El estado seleccionado no es valido.',
        ], [
            'estado' => 'estado',
        ]);

        $propiedad->update([
            'estado' => $validated['estado'],
        ]);

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function destroy(Request $request, Propiedad $propiedad): RedirectResponse
    {
        $this->ensureCanManagePropiedad($request, $propiedad);

        $propiedad->loadMissing('imagenes:id,propiedad_id,ruta_imagen');

        foreach ($propiedad->imagenes as $imagen) {
            if (Storage::disk('public')->exists($imagen->ruta_imagen)) {
                Storage::disk('public')->delete($imagen->ruta_imagen);
            }
        }

        $propiedad->delete();

        return redirect()
            ->route('propiedades.mine')
            ->with('success', 'Publicacion eliminada correctamente.');
    }

    private function fotosMessages(): array
    {
        return [
            'fotos.required' => 'Debes seleccionar al menos una foto.',
            'fotos.array' => 'El formato de fotos no es valido.',
            'fotos.min' => 'Debes seleccionar al menos una foto.',
            'fotos.*.required' => 'Cada foto es obligatoria.',
            'fotos.*.image' => 'Cada archivo debe ser una imagen valida.',
            'fotos.*.mimes' => 'Las fotos deben ser JPG, JPEG, PNG o WEBP.',
            'fotos.*.max' => 'Cada foto debe pesar como maximo 5 MB.',
        ];
    }

    private function fotosAttributes(): array
    {
        return [
            'fotos' => 'fotos',
            'fotos.*' => 'foto',
        ];
    }

    private function datosMessages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser texto.',
            'numeric' => 'El campo :attribute debe ser numerico.',
            'integer' => 'El campo :attribute debe ser un numero entero.',
            'max.string' => 'El campo :attribute no puede superar :max caracteres.',
            'max.numeric' => 'El campo :attribute no puede ser mayor a :max.',
            'min.string' => 'El campo :attribute debe tener al menos :min caracteres.',
            'min.numeric' => 'El campo :attribute debe ser mayor o igual a :min.',
            'between.numeric' => 'El campo :attribute debe estar entre :min y :max.',
            'in' => 'El valor seleccionado para :attribute no es valido.',
            'exists' => 'El :attribute seleccionado no existe.',
        ];
    }

    private function datosAttributes(): array
    {
        return [
            'titulo' => 'titulo',
            'descripcion' => 'descripcion',
            'precio' => 'precio',
            'tipo' => 'tipo de operacion',
            'estado' => 'estado',
            'direccion' => 'direccion',
            'latitud' => 'latitud',
            'longitud' => 'longitud',
            'habitaciones' => 'habitaciones',
            'banos' => 'banos',
            'area' => 'area',
            'tipo_propiedad_id' => 'tipo de propiedad',
            'departamento' => 'departamento',
            'provincia' => 'provincia',
            'distrito' => 'distrito',
        ];
    }

    private function ensureCanManagePropiedad(Request $request, Propiedad $propiedad): void
    {
        $user = $request->user();

        if ($propiedad->user_id !== $user->id && ! $user->esAdmin()) {
            abort(403);
        }
    }
}
