<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Contracts\User as SocialUserContract;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class SocialAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_redirected_to_google_login(): void
    {
        config()->set('services.google.client_id', 'google-client-id-test');
        config()->set('services.google.client_secret', 'google-client-secret-test');
        config()->set('services.google.redirect', 'http://localhost/auth/google/callback');

        $provider = \Mockery::mock(Provider::class);
        $provider->shouldReceive('redirect')->once()->andReturn(redirect('/oauth/mock/google'));

        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturn($provider);

        $response = $this->get(route('auth.social.redirect', ['provider' => 'google']));

        $response->assertRedirect('/oauth/mock/google');
    }

    public function test_user_can_login_with_google_callback(): void
    {
        config()->set('services.google.client_id', 'google-client-id-test');
        config()->set('services.google.client_secret', 'google-client-secret-test');
        config()->set('services.google.redirect', 'http://localhost/auth/google/callback');
        Storage::fake('public');
        Http::fake([
            'https://example.com/avatar.jpg' => Http::response('fake-image', 200, ['Content-Type' => 'image/jpeg']),
        ]);

        $socialUser = \Mockery::mock(SocialUserContract::class);
        $socialUser->shouldReceive('getId')->once()->andReturn('google-123');
        $socialUser->shouldReceive('getEmail')->once()->andReturn('socialuser@example.com');
        $socialUser->shouldReceive('getName')->once()->andReturn('Alex Aquino');
        $socialUser->shouldReceive('getAvatar')->once()->andReturn('https://example.com/avatar.jpg');

        $provider = \Mockery::mock(Provider::class);
        $provider->shouldReceive('user')->once()->andReturn($socialUser);

        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturn($provider);

        $response = $this->get(route('auth.social.callback', ['provider' => 'google']));

        $user = User::query()->where('email', 'socialuser@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('google', $user->provider);
        $this->assertSame('google-123', $user->provider_id);
        $this->assertSame('Alex', $user->name);
        $this->assertSame('Aquino', $user->apellidos);
        $this->assertNotNull($user->foto_perfil);
        Storage::disk('public')->assertExists($user->foto_perfil);
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('profile_dni_required');
    }

    public function test_invalid_social_provider_returns_404(): void
    {
        $this->get('/auth/twitter/redirect')->assertNotFound();
        $this->get('/auth/twitter/callback')->assertNotFound();
    }
}
