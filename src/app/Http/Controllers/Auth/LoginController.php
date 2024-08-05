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
            'public-link' => config('techTips.allow_public') ? [
                'url' => route('publicTips.index'),
                'text' => config('techTips.public_link_text'),
            ] : false,
            'allow-oath' => (bool) config('services.azure.allow_login'),
        ]);
    }
}
