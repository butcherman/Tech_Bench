<?php

use App\Http\Controllers\Guest\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;
use Illuminate\Support\Facades\Route;

/*
*   Authentication Routes
*/
Route::middleware('guest')->group(function()
{
    //  Primary Login Routes
    Route::get( '/',      HomeController::class)->name('home');
    Route::get( '/login', HomeController::class)->name('login.index');
    Route::post('/login', LoginController::class)->name('login.submit');

    //  Forgot password routes
    Route::get( '/forgot-password', [PasswordController::class,  'index'])->name('password.forgot');
    Route::post('/forgot-password', [PasswordController::class,  'store'])->name('password.store');
    Route::get( '/reset-password',  [PasswordController::class, 'resetPassword'])->name('password.reset');
    Route::put( '/reset-password',  [PasswordController::class,   'submitReset'])->name('password.reset');
});

/*
*   Basic Authenticated User Routes
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/logout',   LogoutController::class)->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/about',     AboutController::class)           ->name('about');
});


Route::get('/settings', function()
{
    return 'settings';
})->name('settings.index');
