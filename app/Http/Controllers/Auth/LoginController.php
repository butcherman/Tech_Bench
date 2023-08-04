<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * Login Form
 */
class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        return Inertia::render('Auth/Login', [
            'allow_oath' => (bool) config('services.azure.allow_login'),
        ]);
    }
}
