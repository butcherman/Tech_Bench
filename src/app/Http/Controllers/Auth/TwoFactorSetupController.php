<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TwoFactorSetupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Auth/TwoFactorSetup', [
            'required' => config('auth.twoFa.required'),
            'methods' => collect(config('auth.twoFa.methods'))
                ->filter()
                ->keys()
                ->all(),
        ]);
    }
}
