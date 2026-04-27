<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $usuarios = User::query()
            ->withCount('propiedades')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $metricas = [
            'usuarios_total' => User::query()->count(),
            'usuarios_admin' => User::query()->where('rol', 'admin')->count(),
            'usuarios_activos' => User::query()->where('estado', 'activo')->count(),
            'usuarios_inactivos' => User::query()->where('estado', 'inactivo')->count(),
        ];

        return view('admin.PanelAdministrativo.usuarios', [
            'usuarios' => $usuarios,
            'metricas' => $metricas,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'apellidos' => ['nullable', 'string', 'max:160'],
            'email' => ['required', 'string', 'email', 'max:180', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'rol' => ['required', Rule::in(['admin', 'agente', 'cliente'])],
            'estado' => ['required', Rule::in(['activo', 'inactivo'])],
            'tipo_persona' => ['required', Rule::in(['natural', 'empresa'])],
        ]);

        User::query()->create([
            'name' => trim($validated['name']),
            'apellidos' => $this->cleanNullableText($validated['apellidos'] ?? null),
            'email' => trim($validated['email']),
            'password' => $validated['password'],
            'rol' => $validated['rol'],
            'estado' => $validated['estado'],
            'tipo_persona' => $validated['tipo_persona'],
        ]);

        return redirect()
            ->route('admin.PanelAdministrativo.usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $admin = $this->ensureAdmin($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'apellidos' => ['nullable', 'string', 'max:160'],
            'email' => ['required', 'string', 'email', 'max:180', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'rol' => ['required', Rule::in(['admin', 'agente', 'cliente'])],
            'estado' => ['required', Rule::in(['activo', 'inactivo'])],
            'tipo_persona' => ['required', Rule::in(['natural', 'empresa'])],
        ]);

        if ($admin->id === $user->id && ($validated['rol'] !== 'admin' || $validated['estado'] !== 'activo')) {
            return redirect()
                ->route('admin.PanelAdministrativo.usuarios.index')
                ->with('error', 'No puedes quitarte permisos de administrador ni bloquear tu cuenta.');
        }

        if ($this->wouldLeaveWithoutActiveAdmin($user, $validated)) {
            return redirect()
                ->route('admin.PanelAdministrativo.usuarios.index')
                ->with('error', 'Debe existir al menos un administrador activo.');
        }

        $payload = [
            'name' => trim($validated['name']),
            'apellidos' => $this->cleanNullableText($validated['apellidos'] ?? null),
            'email' => trim($validated['email']),
            'rol' => $validated['rol'],
            'estado' => $validated['estado'],
            'tipo_persona' => $validated['tipo_persona'],
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        $user->update($payload);

        return redirect()
            ->route('admin.PanelAdministrativo.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function actualizarEstado(Request $request, User $user): RedirectResponse
    {
        $admin = $this->ensureAdmin($request);

        if ($admin->id === $user->id) {
            return redirect()
                ->route('admin.PanelAdministrativo.usuarios.index')
                ->with('error', 'No puedes bloquear tu propia cuenta.');
        }

        $validated = $request->validate([
            'estado' => ['required', 'in:activo,inactivo'],
        ]);

        if (
            $user->esAdmin() &&
            $validated['estado'] === 'inactivo' &&
            $this->activeAdminsCount() <= 1 &&
            $user->estado === 'activo'
        ) {
            return redirect()
                ->route('admin.PanelAdministrativo.usuarios.index')
                ->with('error', 'No puedes bloquear al ultimo administrador activo.');
        }

        $user->update([
            'estado' => $validated['estado'],
        ]);

        return redirect()
            ->route('admin.PanelAdministrativo.usuarios.index')
            ->with('success', 'Estado de usuario actualizado correctamente.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $admin = $this->ensureAdmin($request);

        if ($admin->id === $user->id) {
            return redirect()
                ->route('admin.PanelAdministrativo.usuarios.index')
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        if ($user->esAdmin() && User::query()->where('rol', 'admin')->count() <= 1) {
            return redirect()
                ->route('admin.PanelAdministrativo.usuarios.index')
                ->with('error', 'No puedes eliminar al ultimo administrador.');
        }

        $user->delete();

        return redirect()
            ->route('admin.PanelAdministrativo.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }

    private function ensureAdmin(Request $request): User
    {
        $user = $request->user();

        if ($user === null || ! $user->esAdmin()) {
            abort(403);
        }

        return $user;
    }

    private function wouldLeaveWithoutActiveAdmin(User $target, array $validated): bool
    {
        $esAdminActivo = $target->rol === 'admin' && $target->estado === 'activo';
        if (! $esAdminActivo) {
            return false;
        }

        $siguienteRol = $validated['rol'] ?? $target->rol;
        $siguienteEstado = $validated['estado'] ?? $target->estado;

        if ($siguienteRol === 'admin' && $siguienteEstado === 'activo') {
            return false;
        }

        return $this->activeAdminsCount() <= 1;
    }

    private function cleanNullableText(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }

    private function activeAdminsCount(): int
    {
        return User::query()
            ->where('rol', 'admin')
            ->where('estado', 'activo')
            ->count();
    }
}
