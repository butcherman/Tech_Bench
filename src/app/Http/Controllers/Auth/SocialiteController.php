<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Socialite\AuthorizeUser;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * If feature is disabled, show 404 page not found
     */
    public function __construct(protected AuthorizeUser $authObj) {}

    /**
     * Redirect the user to the Microsoft Login page
     */
    public function redirectAuth(): RedirectResponse
    {
        if (! config('services.azure.allow_login')) {
            abort(404);
        }

        Log::info('Redirecting visitor to Microsoft Azure Authentication');

        return Socialite::driver('azure')->redirect();
    }

    /**
     * Callback from when a user is properly authenticated from Microsoft
     */
    public function callback(): RedirectResponse
    {
        if (! config('services.azure.allow_login')) {
            abort(404);
        }

        $this->authObj->handle();

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
