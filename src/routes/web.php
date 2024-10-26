<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'welcome-message' => config('app.welcome_message'),
        'home-links' => config('app.home_links'),
        'public-link' => false,
        'allow-oath' => (bool) config('services.azure.allow_login'),
    ]);
});
