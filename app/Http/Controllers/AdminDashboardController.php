<?php

namespace App\Http\Controllers;

use App\Models\PortalVisita;
use App\Models\Propiedad;
use App\Models\User;
use App\Models\Visita;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $metricas = [
            'usuarios_total' => User::query()->count(),
            'usuarios_admin' => User::query()->where('rol', 'admin')->count(),
            'usuarios_cliente' => User::query()->where('rol', 'cliente')->count(),
            'usuarios_agente' => User::query()->where('rol', 'agente')->count(),
            'usuarios_activos' => User::query()->where('estado', 'activo')->count(),
            'usuarios_inactivos' => User::query()->where('estado', 'inactivo')->count(),
            'visitas_portal_total' => PortalVisita::query()->count(),
            'visitantes_unicos_portal' => PortalVisita::query()->distinct('visitor_key')->count('visitor_key'),
            'visitas_portal_hoy' => PortalVisita::query()->whereDate('fecha_visita', now()->toDateString())->count(),
            'clics_propiedades_total' => Visita::query()->count(),
            'propiedades_total' => Propiedad::query()->count(),
        ];

        $visitasRawPorDia = PortalVisita::query()
            ->selectRaw('DATE(fecha_visita) as fecha')
            ->selectRaw('COUNT(*) as total')
            ->whereDate('fecha_visita', '>=', now()->subDays(6)->toDateString())
            ->groupByRaw('DATE(fecha_visita)')
            ->pluck('total', 'fecha');

        $visitasUltimos7Dias = collect(range(0, 6))
            ->map(function (int $offset) use ($visitasRawPorDia): array {
                $fecha = Carbon::today()->subDays(6 - $offset)->toDateString();

                return [
                    'fecha' => $fecha,
                    'label' => Carbon::parse($fecha)->format('d/m'),
                    'total' => (int) ($visitasRawPorDia[$fecha] ?? 0),
                ];
            });

        $topPublicaciones = Propiedad::query()
            ->with(['usuario:id,name,apellidos'])
            ->withCount(['visitas', 'favoritos'])
            ->orderByDesc('visitas_count')
            ->orderByDesc('favoritos_count')
            ->latest()
            ->limit(5)
            ->get();

        $publicaciones = Propiedad::query()
            ->with([
                'usuario:id,name,apellidos',
                'tipoPropiedad:id,nombre',
                'ubicacion:id,distrito,departamento',
            ])
            ->withCount(['visitas', 'favoritos', 'contactos'])
            ->orderByDesc('visitas_count')
            ->orderByDesc('favoritos_count')
            ->latest()
            ->paginate(12, ['*'], 'publicaciones_page')
            ->withQueryString();

        return view('admin.dashboard', [
            'metricas' => $metricas,
            'visitasUltimos7Dias' => $visitasUltimos7Dias,
            'topPublicaciones' => $topPublicaciones,
            'publicaciones' => $publicaciones,
        ]);
    }
}
