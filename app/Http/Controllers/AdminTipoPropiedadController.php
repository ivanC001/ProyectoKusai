<?php

namespace App\Http\Controllers;

use App\Models\PortalVisita;
use App\Models\Propiedad;
use App\Models\TipoPropiedad;
use App\Models\Visita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTipoPropiedadController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $tiposPropiedad = TipoPropiedad::query()
            ->withCount('propiedades')
            ->orderBy('nombre')
            ->get();

        $metricas = [
            'tipos_total' => $tiposPropiedad->count(),
            'propiedades_asociadas' => (int) $tiposPropiedad->sum('propiedades_count'),
            'visitas_portal_total' => PortalVisita::query()->count(),
            'clics_propiedades_total' => Visita::query()->count(),
        ];

        $topPublicaciones = Propiedad::query()
            ->with(['usuario:id,name,apellidos'])
            ->withCount(['visitas', 'favoritos'])
            ->orderByDesc('visitas_count')
            ->orderByDesc('favoritos_count')
            ->latest()
            ->limit(4)
            ->get();

        return view('admin.PanelAdministrativo.index', [
            'tiposPropiedad' => $tiposPropiedad,
            'metricas' => $metricas,
            'topPublicaciones' => $topPublicaciones,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:120', 'unique:tipos_propiedad,nombre'],
        ]);

        TipoPropiedad::create([
            'nombre' => trim($validated['nombre']),
        ]);

        return redirect()
            ->route('admin.PanelAdministrativo')
            ->with('success', 'Tipo de terreno registrado correctamente.');
    }

    public function update(Request $request, TipoPropiedad $tipoPropiedad): RedirectResponse
    {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:120', 'unique:tipos_propiedad,nombre,'.$tipoPropiedad->id],
        ]);

        $tipoPropiedad->update([
            'nombre' => trim($validated['nombre']),
        ]);

        return redirect()
            ->route('admin.PanelAdministrativo')
            ->with('success', 'Tipo de terreno actualizado correctamente.');
    }

    public function destroy(Request $request, TipoPropiedad $tipoPropiedad): RedirectResponse
    {
        $this->ensureAdmin($request);

        if ($tipoPropiedad->propiedades()->exists()) {
            return redirect()
                ->route('admin.PanelAdministrativo')
                ->with('error', 'No puedes eliminar este tipo porque tiene publicaciones asociadas.');
        }

        $tipoPropiedad->delete();

        return redirect()
            ->route('admin.PanelAdministrativo')
            ->with('success', 'Tipo de terreno eliminado correctamente.');
    }

    private function ensureAdmin(Request $request): void
    {
        $user = $request->user();

        if ($user === null || ! $user->esAdmin()) {
            abort(403);
        }
    }
}
