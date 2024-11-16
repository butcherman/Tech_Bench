<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\TwoFactorController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| Authentication Routes not handled by Fortify
|-------------------------------------------------------------------------------
*/
Route::middleware(['guest', 'throttle:50,120'])->group(function () {
    Route::get('/', LoginController::class)->name('home');

    /*
    |---------------------------------------------------------------------------
    | Socialite Routes (Azure Login)
    |---------------------------------------------------------------------------
    */
    Route::controller(SocialiteController::class)->group(function () {
        Route::get('auth/redirect', 'redirectAuth')->name('azure-login');
        Route::get('auth/callback', 'callback')->name('azure-callback');
    });
});

/*
|-------------------------------------------------------------------------------
| Two Factor Authentication Routes
|-------------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->name('2fa.')
    ->controller(TwoFactorController::class)
    ->group(function () {
        Route::get('two-factor-authentication', 'show')->name('show');
        Route::put('two-factor-authentication', 'update')->name('update');
    });
