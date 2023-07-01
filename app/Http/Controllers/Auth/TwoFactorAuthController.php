<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerificationCodeRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * Two-Factor Authentication
 */
class TwoFactorAuthController extends Controller
{
    /**
     * Display the 2FA Verification Form
     */
    public function get()
    {
        return Inertia::render('Auth/TwoFactorAuth');
    }

    /**
     * Validate the 2FA code and set remember device if needed
     */
    public function set(VerificationCodeRequest $request)
    {
        if($cookie = $request->verifyCode()) {
            session()->put('2fa_verified', true);

            if(is_bool($cookie)) {
                return redirect()->intended(route('dashboard'));
            }

            //  If a Remember Device token is generated, add that cookie to the browser
            return redirect()->intended(route('dashboard'))->withCookie('remember_device', $cookie, 259200);
        }

        return back()->withErrors(['2fa' => 'Sorry, the validation code was incorrect']);
    }
}
