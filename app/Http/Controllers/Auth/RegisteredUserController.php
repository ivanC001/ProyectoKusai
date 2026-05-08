<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Throwable;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tipo_persona' => ['required', 'in:natural,empresa'],
            'name' => ['required', 'string', 'max:120'],
            'apellidos' => ['nullable', 'string', 'max:120', Rule::requiredIf($request->input('tipo_persona') === 'natural')],
            'empresa' => ['nullable', 'string', 'max:180', Rule::requiredIf($request->input('tipo_persona') === 'empresa')],
            'nombre_comercial' => ['nullable', 'string', 'max:180'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'telefono' => ['required', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'dni' => ['nullable', 'string', 'max:20', 'unique:users,dni', Rule::requiredIf($request->input('tipo_persona') === 'natural')],
            'ruc' => ['nullable', 'string', 'max:20', 'unique:users,ruc', Rule::requiredIf($request->input('tipo_persona') === 'empresa')],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'acepta_terminos' => ['accepted'],
        ], [
            'apellidos.required' => 'Los apellidos son obligatorios para persona natural.',
            'empresa.required' => 'La razon social es obligatoria para persona juridica.',
            'dni.required' => 'El DNI es obligatorio para persona natural.',
            'ruc.required' => 'El RUC es obligatorio para persona juridica.',
        ]);

        $user = User::create([
            'tipo_persona' => $request->tipo_persona,
            'name' => $request->name,
            'apellidos' => $request->apellidos ? trim((string) $request->apellidos) : null,
            'empresa' => $request->empresa ? trim((string) $request->empresa) : null,
            'nombre_comercial' => $request->nombre_comercial,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'whatsapp' => $request->whatsapp,
            'dni' => $request->dni ? trim((string) $request->dni) : null,
            'ruc' => $request->ruc ? trim((string) $request->ruc) : null,
            'password' => $request->password,
            'rol' => 'cliente',
            'estado' => 'activo',
        ]);

        Auth::login($user);

        try {
            $user->sendEmailVerificationCode();
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('verification.notice')
                ->with('warning', 'Tu cuenta fue creada, pero no se pudo enviar el codigo de verificacion. Revisa la configuracion de correo e intenta reenviar.');
        }

        return redirect()->route('verification.notice');
    }
}
