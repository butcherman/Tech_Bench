<?php

use App\Http\Controllers\Auth\ForgotPasswordSubmitEmailController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LoginPageController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ResetPasswordSubmitController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\User\FinishSetupController;
use App\Http\Controllers\User\InitializeUserController;
use Illuminate\Support\Facades\Route;

/*
*   Authentication Routes
*/
Route::middleware('guest')->group(function () {
    Route::get('/', LoginPageController::class)->name('home');
    Route::get('/login', LoginPageController::class)->name('login.index');
    Route::post('/login', LoginController::class)->name('login.submit');

    //  Reset a forgotten password
    Route::name('password.')->group(function () {
        Route::inertia('forgot-password', 'Auth/ForgotPassword')->name('forgot');
        Route::post('forgot-password', ForgotPasswordSubmitEmailController::class)->name('submit-email');
        Route::get('reset-password', ResetPasswordController::class)->name('reset');
        Route::post('reset-password', ResetPasswordSubmitController::class)->name('reset-submit');
    });

    //  Initializing a new User
    Route::get('finish-setup/{initLink:token}', InitializeUserController::class)->name('initialize');
    Route::put('finish-setup/{initLink:token}', FinishSetupController::class)->name('finish-setup');

    /**
     * Socialite Routes
     */
    Route::get('auth/redirect', [SocialiteController::class, 'redirectAuth'])->name('azure-login');
    Route::get('auth/callback', [SocialiteController::class, 'callback'])->name('azure-callback');
});

//  Log user out
Route::middleware('auth')->post('logout', LogoutController::class)->name('logout');
