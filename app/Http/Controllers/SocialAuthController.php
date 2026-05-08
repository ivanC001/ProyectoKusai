<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();

        $user = User::where('provider_id', $socialUser->id)->first();

        if (!$user) {

            // buscar por email
            $user = User::where('email', $socialUser->email)->first();

            if ($user) {

                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->id,
                    'avatar' => $socialUser->avatar,
                ]);

            } else {

                $user = User::create([
                    'name' => $socialUser->name,
                    'email' => $socialUser->email,
                    'provider' => $provider,
                    'provider_id' => $socialUser->id,
                    'avatar' => $socialUser->avatar,
                    'password' => bcrypt(Str::random(16)),
                ]);
            }
        }

        Auth::login($user);

        return redirect('/dashboard');
    }
}