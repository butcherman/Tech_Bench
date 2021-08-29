<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\User\UserSettingsController;
use App\Http\Controllers\User\ChangePasswordController;

/*
*   Standard Routes for users that have been successfully Authenticated
*/
Route::middleware('auth')->group(function()
{
    Route::get('dashboard',     DashboardController::class)->name('dashboard');
    Route::get('about',         AboutController::class)    ->name('about');

    Route::resource('settings', UserSettingsController::class);
    Route::resource('password', ChangePasswordController::class);
});
