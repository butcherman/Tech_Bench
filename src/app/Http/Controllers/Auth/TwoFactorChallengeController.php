<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;

class TwoFactorChallengeController extends Controller
{
    public function __construct(protected TwoFactorService $svc) {}

    /**
     * Provide the 2FA Challenge
     */
    public function challenge(Request $request)
    {
        $user = $this->svc->getChallengedUser($request);
        $via = $this->svc->getMfaMethod($user);
        $this->svc->provideChallenge($user);

        return Inertia::render('Auth/TwoFactorChallenge', [
            'via' => $via,
            'allow-remember' => config('auth.twoFa.allow_save_device'),
        ]);

    }

    /**
     * Validate the 2FA Challange response
     */
    public function verify(TwoFactorLoginRequest $request)
    {
        $user = $request->challengedUser();
        $valid = $user->validateVerificationCode($request->input('code'));

        if (! $valid) {
            return back()->withErrors([
                'code' => 'The provided code is invalid or has expired',
            ], 'mfa');
        }

        Auth::login($user, $request->remember());

        return app(TwoFactorLoginResponse::class);
    }
}
