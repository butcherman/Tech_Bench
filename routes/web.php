<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Guest\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserEmailNotificationsController;

/*
*   Authentication Routes
*/
Route::middleware('guest')->group(function()
{
    //  Primary Login Routes
    Route::get( '/',      HomeController::class) ->name('home');
    Route::get( '/login', HomeController::class) ->name('login.index');
    Route::post('/login', LoginController::class)->name('login.submit');

    //  Forgot password routes
    Route::get( '/forgot-password', [PasswordController::class, 'index'])        ->name('password.forgot');
    Route::post('/forgot-password', [PasswordController::class, 'store'])        ->name('password.store');
    Route::get( '/reset-password',  [PasswordController::class, 'resetPassword'])->name('password.reset');
    Route::put( '/reset-password',  [PasswordController::class, 'submitReset'])  ->name('password.reset');
});

/*
*   Basic Authenticated User Routes
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/logout',    LogoutController::class)   ->name('logout');
    Route::get( '/dashboard', DashboardController::class)->name('dashboard');
    Route::get( '/about',     AboutController::class)    ->name('about');
});

/*
*   User Settings Routes
*/
Route::middleware('auth')->group(function () {
    //  Primary User Settings
    Route::resource('settings',            UserController::class);
    Route::resource('email-notifications', UserEmailNotificationsController::class);
    //  Change Password
    Route::get('password/{change}', [PasswordController::class, 'edit'])  ->name('password.edit');
    Route::put('password/{id}',     [PasswordController::class, 'update'])->name('password.update');
});

