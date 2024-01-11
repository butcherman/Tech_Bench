<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        return Inertia::render('Auth/Login', [
            'welcome-message' => config('app.welcome_message'),
            'home-links' => config('app.home_links'),
            'allow-oath' => config('services.azure.allow_login'),
        ]);
    }
}
