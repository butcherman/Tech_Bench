<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerificationCodeRequest;
use Inertia\Inertia;

class TwoFactorController extends Controller
{
    /**
     * Display the resource.
     */
    public function show()
    {
        // If the user has already verified, re-route to intended route or dashboard
        if (session()->has('2fa_verified')) {
            return redirect()->intended(route('dashboard'));
        }

        return Inertia::render('Auth/TwoFactorAuth', [
            'allow-remember' => (bool) config('auth.twoFa.allow_save_device'),
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(VerificationCodeRequest $request)
    {
        session()->put('2fa_verified', true);
        $cookie = $request->remember ?
            $request->user()->generateRememberDeviceToken() : null;

        return redirect()
            ->intended(route('dashboard'))
            ->withCookie('remember_device', $cookie, 259200);
    }
}
