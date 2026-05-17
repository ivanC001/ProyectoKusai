<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateVerificacionUsuarioRequest;
use App\Models\VerificacionUsuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificacionUsuarioController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $verificaciones = VerificacionUsuario::query()
            ->with([
                'usuario:id,name,apellidos,email,dni,telefono',
                'verificador:id,name,apellidos',
            ])
            ->latest()
            ->paginate(12);

        $resumen = [
            'total' => VerificacionUsuario::query()->count(),
            'pendientes' => VerificacionUsuario::query()->where('estado', 'pendiente')->count(),
            'aprobadas' => VerificacionUsuario::query()->where('estado', 'aprobado')->count(),
            'rechazadas' => VerificacionUsuario::query()->where('estado', 'rechazado')->count(),
        ];

        return view('admin.verificaciones_usuario.index', [
            'verificaciones' => $verificaciones,
            'resumen' => $resumen,
        ]);
    }

    public function edit(Request $request, VerificacionUsuario $verificaciones_usuario): View
    {
        $this->ensureAdmin($request);

        $verificacion = $verificaciones_usuario->load([
            'usuario:id,name,apellidos,email,dni,telefono,whatsapp,direccion',
            'verificador:id,name,apellidos',
        ]);

        return view('admin.verificaciones_usuario.edit', [
            'verificacion' => $verificacion,
        ]);
    }

    public function update(
        UpdateVerificacionUsuarioRequest $request,
        VerificacionUsuario $verificaciones_usuario
    ): RedirectResponse {
        $verificacion = $verificaciones_usuario;

        $payload = [
            'dni_legible' => $request->boolean('dni_legible'),
            'datos_coinciden' => $request->boolean('datos_coinciden'),
            'contacto_validado' => $request->boolean('contacto_validado'),
            'estado' => (string) $request->input('estado'),
            'observaciones' => $request->filled('observaciones')
                ? trim((string) $request->input('observaciones'))
                : null,
        ];

        $completa = $payload['dni_legible']
            && $payload['datos_coinciden']
            && $payload['contacto_validado'];

        if ($payload['estado'] === 'aprobado' && $completa) {
            $payload['verificado_por'] = $request->user()->id;
            $payload['fecha_verificacion'] = now();
        } else {
            $payload['verificado_por'] = null;
            $payload['fecha_verificacion'] = null;
        }

        $verificacion->update($payload);

        return redirect()
            ->route('admin.verificaciones-usuarios.edit', $verificacion)
            ->with('success', $verificacion->estaAprobada()
                ? 'Usuario aprobado con sello Verificado por Kusay.'
                : 'Solicitud actualizada correctamente.');
    }

    private function ensureAdmin(Request $request): void
    {
        $user = $request->user();
        if ($user === null || ! $user->esAdmin()) {
            abort(403);
        }
    }
}

