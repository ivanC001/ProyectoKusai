<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tipo_persona' => ['required', 'in:natural,empresa'],
            'name' => ['required', 'string', 'max:120'],
            'apellidos' => ['nullable', 'string', 'max:120'],
            'empresa' => ['nullable', 'string', 'max:180'],
            'nombre_comercial' => ['nullable', 'string', 'max:180'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'telefono' => ['required', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'dni' => ['nullable', 'string', 'max:20', 'unique:users,dni'],
            'ruc' => ['nullable', 'string', 'max:20', 'unique:users,ruc'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'acepta_terminos' => ['accepted'],
        ]);

        if ($request->tipo_persona === 'natural' && empty($request->dni)) {
            throw ValidationException::withMessages([
                'dni' => 'El DNI es obligatorio para persona natural.',
            ]);
        }

        if ($request->tipo_persona === 'empresa' && empty($request->ruc)) {
            throw ValidationException::withMessages([
                'ruc' => 'El RUC es obligatorio para empresa.',
            ]);
        }

        $user = User::create([
            'tipo_persona' => $request->tipo_persona,
            'name' => $request->name,
            'apellidos' => $request->apellidos,
            'empresa' => $request->empresa,
            'nombre_comercial' => $request->nombre_comercial,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'whatsapp' => $request->whatsapp,
            'dni' => $request->dni,
            'ruc' => $request->ruc,
            'password' => $request->password,
            'rol' => 'cliente',
            'estado' => 'activo',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('propiedades.create');
    }
}
