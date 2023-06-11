<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::inertia('dashboard', 'Home/Dashboard')->name('dashboard')->breadcrumb('Dashboard');





    Route::get('about', function() { return 'about'; })->name('about');
    Route::get('settings', function() { return 'settings.index'; })->name('settings.index');
    Route::get('password', function() { return 'password.index'; })->name("settings.password.index");
    Route::get('customers', function() { return 'customers.index'; })->name('customers.index');
});
