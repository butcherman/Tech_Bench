<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerificationCodeRequest;
use Inertia\Inertia;

/**
 * Two-Factor Authentication form and verification
 */
class TwoFactorAuthController extends Controller
{
    /**
     * Display the 2FA Verification Form
     */
    public function get()
    {
        // If the user has already verified, re-route to intended route or dashboard
        if (session()->has('2fa_verified') && session()->missing('verify_sms')) {
            return redirect()->intended(route('dashboard'));
        }

        return Inertia::render('Auth/TwoFactorAuth', [
            'allow-remember' => (bool) config('auth.twoFa.allow_save_device'),
        ]);
    }

    /**
     * Validate the 2FA code and set remember device if needed
     */
    public function set(VerificationCodeRequest $request)
    {
        // Validate the 2FA code
        if ($cookie = $request->verifyCode()) {
            // Put verification in session
            session()->put('2fa_verified', true);
            // If this is the first time user is using this number, we must verify it is a working number
            if ($request->user()->receive_sms && $request->session()->has('verify_sms')) {
                $request->user()->update(['sms_verified' => true]);
                $request->session()->forget('verify_sms');
            }

            // If a Remember Device token is not generated, continue to the intended page
            if (is_bool($cookie)) {
                return redirect()->intended(route('dashboard'));
            }

            //  If a Remember Device token is generated, add that cookie to the browser
            return redirect()->intended(route('dashboard'))->withCookie('remember_device', $cookie, 259200);
        }

        return back()->withErrors(['2fa' => 'Sorry, the validation code was incorrect']);
    }
}
