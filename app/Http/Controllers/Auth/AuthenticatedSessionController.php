<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        $user = $request->user();
        $user?->forceFill([
            'ultimo_login' => now(),
        ])->save();

        if ($user !== null && ! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        if ($user !== null && $this->requiresDniCompletion($user)) {
            return redirect()
                ->route('profile.edit')
                ->with('profile_dni_required', 'Para continuar, completa tu DNI en tu perfil.');
        }

        return redirect()->intended('/');
    }

    public function redirectToProvider(string $provider): SymfonyRedirectResponse
    {
        $this->ensureValidProvider($provider);
        $this->ensureProviderIsConfigured($provider);

        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request, string $provider): RedirectResponse
    {
        $this->ensureValidProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
            $providerId = (string) $socialUser->getId();
            if (trim($providerId) === '') {
                throw new \RuntimeException('No se recibio identificador del proveedor OAuth.');
            }

            $email = $socialUser->getEmail();
            if (! is_string($email) || trim($email) === '') {
                $email = sprintf('%s_%s@oauth.kusay.pe', $provider, $providerId);
            }
            $email = Str::lower(trim($email));

            $fullName = trim((string) ($socialUser->getName() ?: $socialUser->getNickname() ?: 'Usuario '.$provider));
            [$name, $apellidos] = $this->splitNameAndLastName($fullName);
            $avatarUrl = $socialUser->getAvatar();

            $user = User::query()
                ->where('provider', $provider)
                ->where('provider_id', $providerId)
                ->first();

            if ($user === null) {
                $user = User::query()->where('email', $email)->first();
            }

            if ($user !== null) {
                $avatarLocalPath = $this->persistSocialAvatar($avatarUrl, $user->foto_perfil);

                $user->forceFill([
                    'provider' => $provider,
                    'provider_id' => $providerId,
                    'avatar' => $avatarUrl,
                    'name' => $user->name ?: $name,
                    'apellidos' => $user->apellidos ?: $apellidos,
                    'foto_perfil' => $avatarLocalPath ?: $user->foto_perfil,
                    'email_verified_at' => $user->email_verified_at ?: now(),
                    'estado' => $user->estado ?: 'activo',
                    'ultimo_login' => now(),
                ])->save();
            } else {
                $avatarLocalPath = $this->persistSocialAvatar($avatarUrl);

                $user = User::query()->create([
                    'name' => $name,
                    'apellidos' => $apellidos,
                    'email' => $email,
                    'password' => bcrypt(Str::random(32)),
                    'provider' => $provider,
                    'provider_id' => $providerId,
                    'avatar' => $avatarUrl,
                    'foto_perfil' => $avatarLocalPath,
                    'rol' => 'cliente',
                    'tipo_persona' => 'natural',
                    'estado' => 'activo',
                    'email_verified_at' => now(),
                    'ultimo_login' => now(),
                ]);
            }

            Auth::login($user);
            $request->session()->regenerate();

            if ($this->requiresDniCompletion($user)) {
                return redirect()
                    ->route('profile.edit')
                    ->with('profile_dni_required', 'Para continuar, completa tu DNI en tu perfil.');
            }

            return redirect()->intended('/');
        } catch (QueryException $exception) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'No se pudo completar el login social. Verifica tu cuenta e intenta nuevamente.',
                ]);
        } catch (\Throwable $exception) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'No se pudo iniciar sesion con '.$provider.'. Intenta nuevamente.',
                ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function ensureValidProvider(string $provider): void
    {
        if (! in_array($provider, ['google', 'facebook'], true)) {
            abort(404);
        }
    }

    private function ensureProviderIsConfigured(string $provider): void
    {
        $clientId = Config::get("services.{$provider}.client_id");
        $clientSecret = Config::get("services.{$provider}.client_secret");
        $redirect = Config::get("services.{$provider}.redirect");

        if (
            ! is_string($clientId) || trim($clientId) === ''
            || ! is_string($clientSecret) || trim($clientSecret) === ''
            || ! is_string($redirect) || trim($redirect) === ''
        ) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => 'Falta configurar el login con '.ucfirst($provider).' en el archivo .env (client_id, client_secret y redirect).',
            ]);
        }
    }

    private function splitNameAndLastName(string $fullName): array
    {
        $normalized = trim(preg_replace('/\s+/', ' ', $fullName) ?: '');

        if ($normalized === '') {
            return ['Usuario', null];
        }

        $parts = explode(' ', $normalized);
        if (count($parts) === 1) {
            return [$parts[0], null];
        }

        $firstName = array_shift($parts);
        $lastName = trim(implode(' ', $parts));

        return [$firstName ?: 'Usuario', $lastName !== '' ? $lastName : null];
    }

    private function persistSocialAvatar(?string $avatarUrl, ?string $currentPath = null): ?string
    {
        if (! is_string($avatarUrl) || trim($avatarUrl) === '' || ! filter_var($avatarUrl, FILTER_VALIDATE_URL)) {
            return $currentPath;
        }

        try {
            $response = Http::timeout(8)->get($avatarUrl);
            if (! $response->successful()) {
                return $currentPath;
            }

            $contentType = Str::lower((string) $response->header('Content-Type', 'image/jpeg'));
            $extension = match (true) {
                str_contains($contentType, 'png') => 'png',
                str_contains($contentType, 'webp') => 'webp',
                str_contains($contentType, 'gif') => 'gif',
                default => 'jpg',
            };

            $path = 'perfiles/social-'.Str::uuid().'.'.$extension;
            Storage::disk('public')->put($path, $response->body());

            if ($currentPath !== null && $currentPath !== '' && Storage::disk('public')->exists($currentPath)) {
                Storage::disk('public')->delete($currentPath);
            }

            return $path;
        } catch (\Throwable $exception) {
            return $currentPath;
        }
    }

    private function requiresDniCompletion(User $user): bool
    {
        return $user->tipo_persona === 'natural' && trim((string) $user->dni) === '';
    }
}
