<?php

use App\Http\Controllers\Home\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('dashboard', DashboardController::class)->name('dashboard');

Route::get('about', function () {
    return Inertia::render('Home/About');
})->name('about');

Route::get('user-settings', function () {
    return 'user-settings';
})->name('user.user-settings.show');

Route::get('change password', function () {
    return 'change password';
})->name('user.change-password.show');
