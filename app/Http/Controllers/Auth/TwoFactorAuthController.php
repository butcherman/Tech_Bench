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
        if($request->verifyCode()) {
            session()->put('2fa_verified', true);

            //  TODO - send back to original route requested
            return redirect(route('dashboard'));
        }

        return back()->withErrors(['2fa' => 'Sorry, the validation code was incorrect']);
    }
}
