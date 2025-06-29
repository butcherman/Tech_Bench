<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Socialite\AuthorizeUser;
use App\Exceptions\Misc\FeatureDisabledException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController
{
    /**
     * If feature is disabled, show 404 page not found
     */
    public function __construct()
    {
        if (! config('services.azure.allow_login')) {
            throw new FeatureDisabledException('Azure Login');
        }
    }

    /**
     * Redirect the user to the Microsoft Login page
     */
    public function redirectAuth(): RedirectResponse
    {
        Log::info('Redirecting visitor to Microsoft Azure Authentication');

        return Socialite::driver('azure')->redirect();
    }

    /**
     * Callback from when a user is properly authenticated from Microsoft
     */
    public function callback(AuthorizeUser $svc): RedirectResponse
    {
        $svc->handle();

        return redirect()->intended(route('dashboard'));
    }
}
