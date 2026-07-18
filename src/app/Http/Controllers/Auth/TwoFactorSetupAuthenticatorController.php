<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorSetupAuthenticatorController extends Controller
{
    /**
     * Show the form to setup 2FA notifications.
     */
    public function __invoke(): Response
    {
        return Inertia::render('Auth/TwoFactorAppSetup');
    }
}
