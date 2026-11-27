<?php

namespace App\Http\Controllers\Auth;

use App\Enums\TwoFactorMethod;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorSetupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return Inertia::render('Auth/TwoFactorSetup', [
            'required' => config('auth.twoFa.required'),
            'methods' => TwoFactorMethod::getMethods(),
        ]);
    }
}
