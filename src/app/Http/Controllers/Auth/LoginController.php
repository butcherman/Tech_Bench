<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    /**
     * Show the Login page
     */
    public function __invoke(): Response
    {
        return Inertia::render('Auth/Login', [
            'welcome-message' => config('app.welcome_message'),
            'home-links' => config('app.home_links'),
            'public-link' => config('tech-tips.allow_public')
                ? [
                    'url' => route('publicTips.index'),
                    'text' => config('tech-tips.public_link_text'),
                ]
                : false,
            'allow-oath' => (bool) config('services.azure.allow_login'),
        ]);
    }
}
