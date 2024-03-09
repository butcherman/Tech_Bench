<?php

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\Home\PhoneTypesController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.secure')->group(function () {
    Route::get('dashboard', DashboardController::class)
        ->name('dashboard')
        ->breadcrumb('Dashboard');
    Route::get('about', AboutController::class)
        ->name('about')
        ->breadcrumb('About', 'dashboard');
    Route::get('phone-types', [PhoneTypesController::class, 'index'])
        ->name('phone-types');
});
