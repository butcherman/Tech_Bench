<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerificationCodeRequest;
use App\Services\Auth\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Contracts\FailedTwoFactorLoginResponse;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;

class TwoFactorController extends TwoFactorAuthenticatedSessionController
{
    /**
     * Validate the 2FA code sent via email and log the user in.
     */
    public function __invoke(TwoFactorLoginRequest $request): FailedTwoFactorLoginResponse|TwoFactorLoginResponse
    {
        $user = $request->challengedUser();

        if (!$user->validateVerificationCode($request->get('code'))) {
            return app(FailedTwoFactorLoginResponse::class);
        }

        $this->guard->login($user, $request->remember());

        $request->session()->regenerate();

        return app(TwoFactorLoginResponse::class);
    }
}
