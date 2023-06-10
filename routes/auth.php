<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function() {
    Route::inertia('/', 'Auth/Login');





    Route::get('auth/redirect', function() {
        return 'socialite login';
    })-> name('azure-login');

    Route::get('forgot-pass', function() { return 'forgot password'; })->name('password.forgot');
});