<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Guest\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ResetPasswordSubmitController;
use App\Http\Controllers\Auth\ForgotPasswordEmailController;
use App\Http\Controllers\Auth\ForgotPasswordSubmitEmailController;

/*
*   Authentication Routes
*/
Route::middleware('guest')->group(function()
{
    //  Primary Login Routes
    Route::get( '/',      HomeController::class) ->name('home');
    Route::get( '/login', HomeController::class) ->name('login.index');
    Route::post('/login', LoginController::class)->name('login.submit');

    //  Reset a forgotten password
    Route::name('password.')->group(function()
    {
        Route::get( 'forgot-password', ForgotPasswordEmailController::class)      ->name('forgot');
        Route::post('forgot-password', ForgotPasswordSubmitEmailController::class)->name('submit-email');
        Route::get( 'reset-password',  ResetPasswordController::class)            ->name('reset');
        Route::post('reset-password',  ResetPasswordSubmitController::class)      ->name('reset-submit');
    });
});

//  Log user out
Route::middleware('auth')->post('/logout', LogoutController::class)->name('logout');
