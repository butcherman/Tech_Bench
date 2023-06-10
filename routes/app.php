<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::inertia('dashboard', 'Home/Dashboard')->name('dashboard');
});
