<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended('/');
        }

        $email = (string) $user->email;
        $atPos = strpos($email, '@');
        $maskedEmail = $email;
        if ($atPos !== false && $atPos > 2) {
            $prefix = substr($email, 0, 2);
            $domain = substr($email, $atPos);
            $maskedEmail = $prefix.str_repeat('*', max(2, $atPos - 2)).$domain;
        }

        return view('auth.verify-email', [
            'maskedEmail' => $maskedEmail,
            'codeExpiresAt' => $user->email_verification_code_expires_at,
        ]);
    }
}
