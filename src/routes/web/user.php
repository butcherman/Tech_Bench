<?php

use App\Http\Controllers\User\RemoveDeviceTokenController;
use App\Http\Controllers\User\UpdateUserAccountController;
use App\Http\Controllers\User\UpdateUserSettingsController;
use App\Http\Controllers\User\UserPasswordController;
use App\Http\Controllers\User\UserSettingsController;
use App\Http\Middleware\CheckPasswordExpiration;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.secure')->prefix('user')->name('user.')->group(function () {
    Route::get('change-password', UserPasswordController::class)
        ->name('change-password.show')
        ->withoutMiddleware(CheckPasswordExpiration::class);

    Route::get('user-settings', UserSettingsController::class)
        ->name('user-settings.show');

    Route::put('user-account/{user}', UpdateUserAccountController::class)
        ->name('user-account.update');

    Route::put('user-settings/{user}', UpdateUserSettingsController::class)
        ->name('user-settings.update');

    Route::delete('remove-device/{user}/{token}', RemoveDeviceTokenController::class)
        ->name('remove-device');
});
