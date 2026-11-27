<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorSetupAuthenticatorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $request->session()->put('two_factor.setup', true);
        $request->session()->put('two_factor.via', 'authenticator');

        return Inertia::render('Auth/TwoFactorSetupAuthenticator');
    }
}
