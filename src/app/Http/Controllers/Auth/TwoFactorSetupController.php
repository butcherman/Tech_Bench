<?php

namespace App\Http\Controllers\Auth;

use App\Enums\TwoFactorMethod;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TwoFactorSetupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $methods = TwoFactorMethod::getMethods();

        if (count($methods) === 1) {
            return redirect($methods[0]['setup-route']);
        }

        return Inertia::render('Auth/TwoFactorSetup', [
            'required' => config('auth.twoFa.required'),
            'methods' => $methods,
        ]);
    }
}
