<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return Inertia::render('Auth/Login', [
            'welcome-message' => config('app.welcome_message'),
            'home-links' => config('app.home_links'),
            'public-link' => false,
            'allow-oath' => config('services.azure.allow_login'),
        ]);
    }
}
