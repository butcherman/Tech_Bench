<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'user_security'])->get('dashboard', function () {
    // return 'dashboard';
    return Inertia::render('Home/Dashboard');
})->name('dashboard');
