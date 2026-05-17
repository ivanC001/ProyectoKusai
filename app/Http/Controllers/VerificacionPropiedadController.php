<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateVerificacionPropiedadRequest;
use App\Models\Propiedad;
use App\Models\VerificacionPropiedad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificacionPropiedadController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $propiedades = Propiedad::query()
            ->with([
                'tipoPropiedad:id,nombre',
                'ubicacion:id,departamento,provincia,distrito',
                'verificacion.verificador:id,name,apellidos',
            ])
            ->latest()
            ->paginate(12);

        $resumen = [
            'total' => $propiedades->total(),
            'completas' => VerificacionPropiedad::query()
                ->where('documentos_revisados', true)
                ->where('visita_confirmada', true)
                ->where('vendedor_identificado', true)
                ->count(),
        ];
        $resumen['pendientes'] = max($resumen['total'] - $resumen['completas'], 0);

        return view('admin.verificaciones_propiedad.index', [
            'propiedades' => $propiedades,
            'resumen' => $resumen,
        ]);
    }

    public function edit(Request $request, Propiedad $verificaciones_propiedad): View
    {
        $this->ensureAdmin($request);

        $propiedad = $verificaciones_propiedad->load([
            'tipoPropiedad:id,nombre',
            'ubicacion:id,departamento,provincia,distrito',
            'usuario:id,name,apellidos,email',
            'verificacion.verificador:id,name,apellidos',
        ]);

        $verificacion = $propiedad->verificacion ?: new VerificacionPropiedad([
            'propiedad_id' => $propiedad->id,
            'documentos_revisados' => false,
            'visita_confirmada' => false,
            'vendedor_identificado' => false,
        ]);

        return view('admin.verificaciones_propiedad.edit', [
            'propiedad' => $propiedad,
            'verificacion' => $verificacion,
        ]);
    }

    public function update(
        UpdateVerificacionPropiedadRequest $request,
        Propiedad $verificaciones_propiedad
    ): RedirectResponse {
        $propiedad = $verificaciones_propiedad;

        $payload = [
            'documentos_revisados' => $request->boolean('documentos_revisados'),
            'visita_confirmada' => $request->boolean('visita_confirmada'),
            'vendedor_identificado' => $request->boolean('vendedor_identificado'),
        ];

        $completa = $payload['documentos_revisados']
            && $payload['visita_confirmada']
            && $payload['vendedor_identificado'];

        $payload['verificado_por'] = $completa ? $request->user()->id : null;
        $payload['fecha_verificacion'] = $completa ? now() : null;

        VerificacionPropiedad::query()->updateOrCreate(
            ['propiedad_id' => $propiedad->id],
            $payload
        );

        return redirect()
            ->route('admin.verificaciones-propiedad.edit', $propiedad)
            ->with('success', $completa
                ? 'Propiedad verificada correctamente con sello Kusay.'
                : 'Estado de verificacion actualizado. Aun faltan criterios por completar.');
    }

    private function ensureAdmin(Request $request): void
    {
        $user = $request->user();
        if ($user === null || ! $user->esAdmin()) {
            abort(403);
        }
    }
}
