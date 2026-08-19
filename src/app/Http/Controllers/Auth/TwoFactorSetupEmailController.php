<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\TwoFactorService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Laravel\Fortify\Events\TwoFactorAuthenticationConfirmed;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;

class TwoFactorSetupEmailController extends Controller
{
    public function __construct(protected TwoFactorService $svc) {}

    public function setup(TwoFactorLoginRequest $request)
    {
        /** @var User */
        $user = $request->challengedUser();
        $user->generateVerificationCode();

        return Inertia::render('Auth/TwoFactorSetupEmail', [
            'allowRemember' => config('auth.twoFa.allow_save_device'),
        ]);
    }

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

        $user->forceFill([
            'two_factor_via' => 'email',
            'two_factor_confirmed_at' => now(),
        ])->save();

        TwoFactorAuthenticationConfirmed::dispatch($user);

        return app(TwoFactorLoginResponse::class);
    }
}
