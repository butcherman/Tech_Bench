<?php

namespace App\Http\Controllers\Auth;

use App\Facades\CacheData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResetPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->input('email'),
            'token' => $request->route('token'),
            'rules' => CacheData::passwordRules(),
            'welcome-message' => fn () => config('app.welcome_message'),
            'home-links' => fn () => config('app.home_links'),
            'public-link' => fn () => config('tech-tips.allow_public')
                ? [
                    'url' => route('publicTips.index'),
                    'text' => config('tech-tips.public_link_text'),
                ] : false,
        ]);
    }
}
