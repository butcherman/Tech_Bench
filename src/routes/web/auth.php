<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\TwoFactorController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['guest', 'throttle:50,120'])->group(function () {
    Route::get('/', LoginController::class)->name('home');
    Route::get('login', LoginController::class);
    Route::middleware(['throttle:login'])
        ->post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login');

    /**
     * Forgot Password Routes
     */
    Route::name('password.')->group(function () {
        Route::inertia('forgot-password', 'Auth/ForgotPassword')->name('forgot');
        Route::get('reset-password', ResetPasswordController::class)->name('reset');
    });

    /**
     * Socialite Routes (Azure Login)
     */
    Route::get('auth/redirect', [SocialiteController::class, 'redirectAuth'])
        ->name('azure-login');
    Route::get('auth/callback', [SocialiteController::class, 'callback'])
        ->name('azure-callback');
});

/*
|--------------------------------------------------------------------------
| Two-Factor Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->name('2fa.')->group(function () {
    Route::get('two-factor-authentication', [TwoFactorController::class, 'show'])
        ->name('show');
    Route::put('two-factor-authentication', [TwoFactorController::class, 'update'])
        ->name('update');
});
