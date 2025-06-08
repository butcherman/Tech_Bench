<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TwoFactorSetupAuthenticatorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->user()->two_factor_via = 'authenticator';
        $request->user()->save();

        return Inertia::render('Auth/TwoFactorAppSetup');
    }
}
