<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\FinishSetupController;
use App\Http\Controllers\User\InitializeUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ResetPasswordSubmitController;
use App\Http\Controllers\Auth\ForgotPasswordSubmitEmailController;

/*
*   Authentication Routes
*/
Route::middleware('guest')->group(function()
{
    Route::inertia('/',      'Auth/Login')           ->name('home');
    Route::inertia('/login', 'Auth/Login')           ->name('login.index');
    Route::post('/login',     LoginController::class)->name('login.submit');

    //  Reset a forgotten password
    Route::name('password.')->group(function()
    {
        Route::inertia('forgot-password', 'Auth/ForgotPassword')                      ->name('forgot');
        Route::post('forgot-password',     ForgotPasswordSubmitEmailController::class)->name('submit-email');
        Route::get( 'reset-password',      ResetPasswordController::class)            ->name('reset');
        Route::post('reset-password',      ResetPasswordSubmitController::class)      ->name('reset-submit');
    });

    //  Initializing a new User
    // Route::get('finish-setup/{token}', InitializeUserController::class)->name('initialize');
    // Route::put('finish-setup/{token}', FinishSetupController::class)->name('finish-setup');
});

//  Log user out
Route::middleware('auth')->post('logout', LogoutController::class)->name('logout');
