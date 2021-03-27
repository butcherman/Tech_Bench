<?php

use App\Http\Controllers\Home\AuthController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\Home\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
*   Authentication Routes
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/',                 [HomeController::class, 'index'])->name('home');
    Route::get('/login',            [HomeController::class, 'index'])->name('login');
    Route::post('/login',           [AuthController::class, 'login'])->name('login');
    Route::get('/forgot-password',  [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'getResetLink'])->name('forgot-password');
    Route::get('/reset-password',   [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('/reset-password',  [AuthController::class, 'submitReset'])->name('password.reset');
});





Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});






Route::get('/del-session', function()
{
    Auth::logout();
    session()->flush();

    return 'logged out';
});
