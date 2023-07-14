<?php

use App\Http\Controllers\Home\AboutController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'user_security'])->group(function () {
    Route::inertia('dashboard', 'Home/Dashboard')->name('dashboard')->breadcrumb('Dashboard');
    Route::get('about', AboutController::class)->name('about')->breadcrumb('About', 'dashboard');

    Route::get('customers', function () {
        return 'customers.index';
    })->name('customers.index');

    Route::get('customers/{name}', function ($name) {
        return 'customers.show';
    })->name('customers.show');

    Route::get('tech-tips', function () {
        return 'tech-tips.index';
    })->name('tech-tips.index');

    Route::get('tech-tips/{name}', function ($name) {
        return 'tech-tips.show';
    })->name('tech-tips.show');
});
