<?php

namespace App\Http\Controllers;

use App\Models\ImagenPropiedad;
use App\Models\Propiedad;
use App\Models\TipoPropiedad;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function index(Request $request): View
    {
        $tiposPropiedad = TipoPropiedad::query()
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        $filtros = [
            'operacion' => in_array($request->string('operacion')->toString(), ['venta', 'alquiler'], true)
                ? $request->string('operacion')->toString()
                : '',
            'tipo_propiedad_id' => $request->integer('tipo_propiedad_id') > 0
                ? $request->integer('tipo_propiedad_id')
                : null,
            'ubicacion' => trim($request->string('ubicacion')->toString()),
            'ciudad' => trim($request->string('ciudad')->toString()),
            'precio_min' => $this->numericOrNull($request->input('precio_min')),
            'precio_max' => $this->numericOrNull($request->input('precio_max')),
            'area_min' => $this->numericOrNull($request->input('area_min')),
            'dormitorios' => $request->integer('dormitorios') > 0
                ? $request->integer('dormitorios')
                : null,
            'orden' => in_array($request->string('orden')->toString(), ['recientes', 'precio_asc', 'precio_desc'], true)
                ? $request->string('orden')->toString()
                : 'recientes',
        ];

        if ($filtros['tipo_propiedad_id'] !== null && ! $tiposPropiedad->contains('id', $filtros['tipo_propiedad_id'])) {
            $filtros['tipo_propiedad_id'] = null;
        }

        $query = Propiedad::query()
            ->where('estado', 'disponible')
            ->with([
                'usuario:id,name,apellidos',
                'tipoPropiedad:id,nombre',
                'ubicacion:id,departamento,provincia,distrito',
                'portadaImagen',
            ])
            ->withCount(['imagenes', 'contactos', 'favoritos', 'visitas']);

        $this->applyFilters($query, $filtros);

        $destacadas = (clone $query)
            ->orderByDesc('visitas_count')
            ->orderByDesc('contactos_count')
            ->latest()
            ->limit(3)
            ->get();

        match ($filtros['orden']) {
            'precio_asc' => $query->orderBy('precio'),
            'precio_desc' => $query->orderByDesc('precio'),
            default => $query->latest(),
        };

        $totalResultados = (clone $query)->count();
        $propiedades = $query->paginate(9)->withQueryString();

        $ciudadesTop = Ubicacion::query()
            ->select('ubicaciones.distrito', 'ubicaciones.departamento')
            ->selectRaw('COUNT(propiedades.id) as propiedades_count')
            ->join('propiedades', 'propiedades.ubicacion_id', '=', 'ubicaciones.id')
            ->where('propiedades.estado', 'disponible')
            ->groupBy('ubicaciones.distrito', 'ubicaciones.departamento')
            ->orderByDesc('propiedades_count')
            ->limit(8)
            ->get();

        return view('portal.index', [
            'tiposPropiedad' => $tiposPropiedad,
            'filtros' => $filtros,
            'totalResultados' => $totalResultados,
            'propiedades' => $propiedades,
            'destacadas' => $destacadas,
            'ciudadesTop' => $ciudadesTop,
        ]);
    }

    public function show(Request $request, Propiedad $propiedad): View
    {
        if ($propiedad->estado !== 'disponible') {
            abort(404);
        }

        $propiedad->loadMissing([
            'usuario:id,name,apellidos,email,telefono,whatsapp',
            'tipoPropiedad:id,nombre',
            'ubicacion:id,departamento,provincia,distrito',
            'imagenes:id,propiedad_id,ruta_imagen',
            'portadaImagen',
        ]);

        $relacionadas = Propiedad::query()
            ->where('estado', 'disponible')
            ->whereKeyNot($propiedad->id)
            ->where('tipo_propiedad_id', $propiedad->tipo_propiedad_id)
            ->with([
                'tipoPropiedad:id,nombre',
                'ubicacion:id,departamento,provincia,distrito',
                'portadaImagen',
            ])
            ->latest()
            ->limit(3)
            ->get();

        return view('portal.show', [
            'propiedad' => $propiedad,
            'relacionadas' => $relacionadas,
        ]);
    }

    public function imagen(Propiedad $propiedad, ImagenPropiedad $imagen)
    {
        if ($propiedad->estado !== 'disponible') {
            abort(404);
        }

        if ($imagen->propiedad_id !== $propiedad->id) {
            abort(404);
        }

        if (! Storage::disk('public')->exists($imagen->ruta_imagen)) {
            abort(404);
        }

        return Storage::disk('public')->response($imagen->ruta_imagen);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<Propiedad>  $query
     * @param  array{
     *     operacion: string,
     *     tipo_propiedad_id: int|null,
     *     ubicacion: string,
     *     ciudad: string,
     *     precio_min: float|null,
     *     precio_max: float|null,
     *     area_min: float|null,
     *     dormitorios: int|null,
     *     orden: string
     * }  $filtros
     */
    private function applyFilters($query, array $filtros): void
    {
        if ($filtros['operacion'] !== '') {
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
}
