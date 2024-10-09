<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerificationCodeRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorController extends Controller
{
    /**
     * If user has not already verified via 2FA, show the 2FA form
     */
    public function show(): Response|RedirectResponse
    {
        if (session()->has('2fa_verified')) {
            return redirect()->intended(route('dashboard'));
        }

        return Inertia::render('Auth/TwoFactorAuth', [
            'allow-remember' => (bool) config('auth.twoFa.allow_save_device'),
        ]);
    }

    /**
     * Validate and save 2FA response
     */
    public function update(VerificationCodeRequest $request): RedirectResponse
    {
        session()->put('2fa_verified', true);
        $cookie = $request->remember
            ? $request->user()->generateRememberDeviceToken()
            : null;

        return redirect()
            ->intended(route('dashboard'))
            ->withCookie('remember_device', $cookie, 259200);
    }
}
