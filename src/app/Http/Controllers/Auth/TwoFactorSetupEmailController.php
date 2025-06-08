<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TwoFactorSetupEmailController extends Controller
{
    /**
     * Setup TwoFactor with Email as the primary response type
     */
    public function __invoke(Request $request)
    {
        $request->user()->generateVerificationCode();

        $request->user()->two_factor_via = 'email';
        $request->user()->save();

        return Inertia::render('Auth/TwoFactorAuth', [
            'allow-remember' => config('auth.twoFa.allow_save_device'),
            'via' => 'email',
        ]);
    }
}
