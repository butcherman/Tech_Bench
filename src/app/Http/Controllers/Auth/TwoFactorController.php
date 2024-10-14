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
     * Show the 2FA Verification Form
     */
    public function show(): Response
    {
        return Inertia::render('Auth/TwoFactorAuth', [
            'allow-remember' => (bool) config('auth.twoFa.allow_save_device'),
        ]);
    }

    /**
     * Validate and save 2FA response
     */
    public function update(VerificationCodeRequest $request): RedirectResponse
    {
        $cookie = $request->user()->processValidCode($request->remember);

        return redirect()
            ->intended(route('dashboard'))
            ->withCookie('remember_device', $cookie, 259200);
    }
}
