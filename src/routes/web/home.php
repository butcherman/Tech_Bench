<?php

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'user_security'])->group(function () {

    Route::get('dashboard', DashboardController::class)
        ->name('dashboard')
        ->breadcrumb('Dashboard');
    Route::get('about', AboutController::class)
        ->name('about')
        ->breadcrumb('About', 'dashboard');
});
