<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\TwoFactorController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| Authentication Routes
|-------------------------------------------------------------------------------
*/

Route::middleware(['guest', 'throttle:50,120'])->group(function () {
    Route::get('/', LoginController::class)->name('home');

    /**
     * Socialite Routes (Azure Login)
     */
    Route::get('auth/redirect', [SocialiteController::class, 'redirectAuth'])
        ->name('azure-login');
    Route::get('auth/callback', [SocialiteController::class, 'callback'])
        ->name('azure-callback');
});

/*
|-------------------------------------------------------------------------------
| Two Factor Authentication Routes
|-------------------------------------------------------------------------------
*/

Route::middleware('auth')->name('2fa.')->group(function () {
    Route::get('two-factor-authentication', [TwoFactorController::class, 'show'])
        ->name('show');

    Route::put('two-factor-authentication', [TwoFactorController::class, 'update'])
        ->name('update');
});
