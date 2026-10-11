<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\TwoFactorChallengeController;
use App\Http\Controllers\Auth\TwoFactorSetupController;
use App\Http\Controllers\Auth\TwoFactorSetupEmailController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| Authentication Routes not handled by Fortify
|-------------------------------------------------------------------------------
*/

Route::middleware(['guest', 'throttle:50,120'])->group(function () {
    Route::get('/', LoginController::class)->name('home');
    Route::prefix('two-factor-authentication')->name('two-factor.')->group(function () {

        Route::post('verify', [TwoFactorChallengeController::class, 'verify'])
            ->name('verify');
    });

    Route::controller(SocialiteController::class)->group(function () {
        Route::get('auth/redirect', 'redirectAuth')->name('azure-login');
        Route::get('auth/callback', 'callback')->name('azure-callback');
    });
});

Route::middleware('auth.secure')->group(function () {
    Route::prefix('two-factor-authentication')->name('two-factor.')->group(function () {
        Route::prefix('setup')->name('setup.')->group(function () {
            Route::get('/', TwoFactorSetupController::class)->name('index');
            Route::inertia('authenticator', 'Auth/TwoFactorSetupAuthenticator', [
                'allow-remember' => config('auth.twoFa.allow_save_device'),
            ])->name('authenticator');
            Route::get('email', [TwoFactorSetupEmailController::class, 'setup'])
                ->name('email');
            Route::post('email', [TwoFactorSetupEmailController::class, 'verify'])
                ->name('email.verify');
        });
    });
});
