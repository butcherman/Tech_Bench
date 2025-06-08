<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TwoFactorSetupAuthenticatorController extends Controller
{
    /**
     * Show the form to setup 2FA notifications.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Auth/TwoFactorAppSetup');
    }
}
