<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\EmailVerificationCodeNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified_with_code(): void
    {
        $user = User::factory()->unverified()->create();

        Event::fake();
        Notification::fake();

        $code = $user->issueEmailVerificationCode();

        $response = $this->actingAs($user)->post(route('verification.code.verify'), [
            'verification_code' => $code,
        ]);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect('/?verified=1');
    }

    public function test_email_is_not_verified_with_invalid_code(): void
    {
        $user = User::factory()->unverified()->create();
        $user->issueEmailVerificationCode();

        $response = $this->actingAs($user)->post(route('verification.code.verify'), [
            'verification_code' => '999999',
        ]);

        $response->assertSessionHasErrors(['verification_code']);
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_user_can_request_new_verification_code(): void
    {
        $user = User::factory()->unverified()->create();
        Notification::fake();

        $response = $this->actingAs($user)->post(route('verification.send'));

        $response->assertSessionHas('status', 'verification-code-sent');
        Notification::assertSentTo($user, EmailVerificationCodeNotification::class);
        $this->assertNotNull($user->fresh()->email_verification_code_hash);
    }

    public function test_unverified_user_is_redirected_to_verification_notice_for_verified_routes(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this
            ->actingAs($user)
            ->get('/propiedades/registro');

        $response->assertRedirect(route('verification.notice'));
    }
}
