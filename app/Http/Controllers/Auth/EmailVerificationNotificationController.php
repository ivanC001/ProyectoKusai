<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/');
        }

        try {
            $request->user()->sendEmailVerificationCode();
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('warning', 'No se pudo reenviar el codigo de verificacion. Revisa la configuracion SMTP e intenta nuevamente.');
        }

        return back()->with('status', 'verification-code-sent');
    }
}
