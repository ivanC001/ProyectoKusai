<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVerificacionUsuarioRequest;
use App\Models\VerificacionUsuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileVerificacionController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $verificacion = $user?->verificacionUsuario;

        return view('profile.verificacion', [
            'user' => $user,
            'verificacion' => $verificacion,
        ]);
    }

    public function store(StoreVerificacionUsuarioRequest $request): RedirectResponse
    {
        $user = $request->user();
        if ($user === null) {
            return redirect()->route('login');
        }

        $validated = $request->validated();
        $verificacionActual = $user->verificacionUsuario;

        if ($verificacionActual !== null && $verificacionActual->estaAprobada()) {
            return redirect()
                ->route('profile.verificacion.edit')
                ->with('success', 'Tu perfil ya está verificado por Kusay.');
        }

        if ($verificacionActual !== null) {
            if (Storage::disk('public')->exists($verificacionActual->dni_frontal_path)) {
                Storage::disk('public')->delete($verificacionActual->dni_frontal_path);
            }
            if (Storage::disk('public')->exists($verificacionActual->dni_reverso_path)) {
                Storage::disk('public')->delete($verificacionActual->dni_reverso_path);
            }
        }

        $folder = 'verificaciones-usuarios/'.$user->id.'/'.Str::lower((string) Str::uuid());
        $dniFrontalPath = $request->file('dni_frontal')->store($folder, 'public');
        $dniReversoPath = $request->file('dni_reverso')->store($folder, 'public');

        VerificacionUsuario::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'dni' => trim((string) $validated['dni']),
                'dni_frontal_path' => $dniFrontalPath,
                'dni_reverso_path' => $dniReversoPath,
                'dni_legible' => false,
                'datos_coinciden' => false,
                'contacto_validado' => false,
                'estado' => 'pendiente',
                'observaciones' => null,
                'verificado_por' => null,
                'fecha_verificacion' => null,
            ]
        );

        if (trim((string) $user->dni) === '') {
            $user->forceFill([
                'dni' => trim((string) $validated['dni']),
            ])->save();
        }

        return redirect()
            ->route('profile.verificacion.edit')
            ->with('success', 'Solicitud enviada. El equipo de Kusay revisara tu perfil.');
    }
}
