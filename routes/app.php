<?php

use App\Http\Controllers\Home\AboutController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'user_security'])->group(function () {
    Route::inertia('dashboard', 'Home/Dashboard')->name('dashboard')->breadcrumb('Dashboard');
    Route::get('about', AboutController::class)->name('about')->breadcrumb('About', 'dashboard');

    // Route::get('password', function () {
    //     return 'password.index';
    // })->name('settings.password.index');
    Route::get('customers', function () {
        return 'customers.index';
    })->name('customers.index');
});
