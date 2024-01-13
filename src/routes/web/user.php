<?php

use App\Http\Controllers\User\UserPasswordController;
use App\Http\Controllers\User\UserSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::apiSingleton('change-password', UserPasswordController::class);
    Route::apiSingleton('user-settings', UserSettingsController::class);
});
