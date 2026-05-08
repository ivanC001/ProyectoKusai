<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailCodeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended('/?verified=1');
        }

        $validated = $request->validate([
            'verification_code' => ['required', 'digits:6'],
        ], [
            'verification_code.required' => 'Ingresa el codigo de verificacion.',
            'verification_code.digits' => 'El codigo debe tener exactamente 6 digitos.',
        ]);

        if (! $user->hasValidEmailVerificationCode($validated['verification_code'])) {
            return back()->withErrors([
                'verification_code' => 'El codigo no es valido o ya vencio. Solicita uno nuevo.',
            ]);
        }

        if ($user->markEmailAsVerified()) {
            $user->clearEmailVerificationCode();
            event(new Verified($user));
        }

        return redirect()->intended('/?verified=1');
    }
}
