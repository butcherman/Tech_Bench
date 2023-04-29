<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class LoginPageController extends Controller
{
    /**
     * Login page for user authentication
     */
    public function __invoke()
    {
        return Inertia::render('Auth/UserLogin', [
            'allow_oath' => (bool) config('services.azure.allow_login'),
        ]);
    }
}
