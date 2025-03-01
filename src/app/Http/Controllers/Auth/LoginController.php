<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LoginController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return Inertia::render('Auth/TechLogin', [
            'welcome-message' => fn() => config('app.welcome_message'),
            'home-links' => fn() => config('app.home_links'),
            'allow-oath' => fn() => config('services.azure.allow_login'),
            'public-link' => fn() => config('tech-tips.allow_public')
                ? [
                    'url' => route('publicTips.index'),
                    'text' => config('tech-tips.public_link_text'),
                ] : false,
        ]);
    }
}
