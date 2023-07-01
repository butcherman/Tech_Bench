<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerificationCodeRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TwoFactorAuthController extends Controller
{
    public function get()
    {
        return Inertia::render('Auth/TwoFactorAuth');
    }

    public function set(VerificationCodeRequest $request)
    {
        if($cookie = $request->verifyCode()) {
            session()->put('2fa_verified', true);

            if(is_bool($cookie)) {
                return redirect(route('dashboard'));
            }

            //  TODO - send back to original route requested
            return redirect(route('dashboard'))->withCookie('remember_device', $cookie, 259200);
        }

        return back()->withErrors(['2fa' => 'Sorry, the validation code was incorrect']);
    }
}
