<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ProviderController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {

            $socialUser = Socialite::driver($provider)->user();

            $user = User::where(['provider' => $provider, 'provider_id' => $socialUser->id])->first();

            if (!$user) {
                $user = User::create([
                    'username' => User::generateUserName($socialUser->getNickname()),
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'provider_token' => $socialUser->token,
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'email_verified_at' => now(),
                    'profile' => $socialUser->getAvatar()
                ]);
            }

            Auth::login($user);

            return redirect("/");
        } catch (\Exception $e) {
            return redirect('/');
        }
    }
}
