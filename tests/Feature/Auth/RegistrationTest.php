<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\EmailVerificationCodeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'tipo_persona' => 'natural',
            'name' => 'Test User',
            'apellidos' => 'Tester Demo',
            'email' => 'test@example.com',
            'telefono' => '+51999999999',
            'dni' => '12345678',
            'password' => 'password',
            'password_confirmation' => 'password',
            'acepta_terminos' => '1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice', absolute: false));
        $this->assertFalse(auth()->user()->hasVerifiedEmail());
        Notification::assertSentTo(User::query()->first(), EmailVerificationCodeNotification::class);
    }

    public function test_company_registration_requires_ruc_and_razon_social(): void
    {
        $response = $this->post('/register', [
            'tipo_persona' => 'empresa',
            'name' => 'Representante Demo',
            'apellidos' => 'Perez',
            'email' => 'empresa@example.com',
            'telefono' => '+51988887777',
            'password' => 'password',
            'password_confirmation' => 'password',
            'acepta_terminos' => '1',
        ]);

        $response->assertSessionHasErrors(['empresa', 'ruc']);
        $this->assertGuest();
    }

    public function test_natural_registration_requires_dni(): void
    {
        $response = $this->post('/register', [
            'tipo_persona' => 'natural',
            'name' => 'Natural Demo',
            'apellidos' => 'Quispe',
            'email' => 'natural@example.com',
            'telefono' => '+51977776666',
            'password' => 'password',
            'password_confirmation' => 'password',
            'acepta_terminos' => '1',
        ]);

        $response->assertSessionHasErrors(['dni']);
        $this->assertGuest();
    }
}
