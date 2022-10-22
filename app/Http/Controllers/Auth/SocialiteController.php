<?php

namespace App\Http\Controllers\Auth;

use App\Actions\SocialiteAuthorization;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;

/**
 * @codeCoverageIgnore
 */
class SocialiteController extends Controller
{
    /**
     * Redirect the user to the Microsoft Login page
     */
    public function redirectAuth()
    {
        if(config('services.azure.allow_login'))
        {
            return Socialite::driver('azure')->redirect();
        }

        return abort(404);
    }

    /**
     * Callback from when a user is properly authenticated from Microsoft
     */
    public function callback()
    {
        if(!config('services.azure.allow_login'))
        {
            return abort(404);
        }

        $socUser   = Socialite::driver('azure')->user();
        $socialite = new SocialiteAuthorization;
        $user      = $socialite->processUser($socUser);

        if(!$user)
        {
            return abort(403, __('auth.failed_oath'));
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
