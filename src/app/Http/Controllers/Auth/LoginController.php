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
            'welcome-message' => fn () => config('app.welcome_message'),
            'home-links' => fn () => config('app.home_links'),
            'allow-oath' => fn () => config('services.azure.allow_login'),
            'public-link' => fn () => config('tech-tips.allow_public')
                ? [
                    'url' => route('publicTips.index'),
                    'text' => config('tech-tips.public_link_text'),
                ] : false,
        ]);
    }
}
