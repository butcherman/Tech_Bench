<?php

namespace App\Http\Controllers\Auth;

use App\Actions\SocialiteAuthorization;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;

/**
 * If OATH is allowed, redirect user to O365 login page for Single Sign On
 *
 * @codeCoverageIgnore
 */
class SocialiteController extends Controller
{
    /**
     * Redirect the user to the Microsoft Login page
     */
    public function redirectAuth()
    {
        if (config('services.azure.allow_login')) {
            return Socialite::driver('azure')->redirect();
        }

        return abort(404);
    }

    /**
     * Callback from when a user is properly authenticated from Microsoft
     */
    public function callback()
    {
        if (! config('services.azure.allow_login')) {
            return abort(404);
        }

        $socUser = Socialite::driver('azure')->user();
        $socialite = new SocialiteAuthorization;
        $user = $socialite->processUser($socUser);

        if (! $user) {
            return redirect(route('login'))->with('warning', 'You do not have permission to Login.  Please contact your system administrator');
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
