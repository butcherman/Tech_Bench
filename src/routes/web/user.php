<?php

use App\Http\Controllers\User\InitializeUserController;
use App\Http\Controllers\User\PinnedLinksController;
use App\Http\Controllers\User\RemoveDeviceController;
use App\Http\Controllers\User\UserPasswordController;
use App\Http\Controllers\User\UserSettingsController;
use App\Http\Middleware\CheckPasswordExpire;
use Illuminate\Support\Facades\Route;

/**
 * Routes for User Settings and Passwords
 */
Route::middleware('auth.secure')->prefix('user')->name('user.')->group(function () {
    Route::get('user-settings', [UserSettingsController::class, 'show'])
        ->name('user-settings.show')
        ->breadcrumb('User Settings', 'dashboard');
    Route::post('user-settings/{user}', [UserSettingsController::class, 'store'])
        ->name('user-settings.store');
    Route::put('user-settings/{user}', [UserSettingsController::class, 'update'])
        ->name('user-settings.update');

    Route::get('change-password', UserPasswordController::class)
        ->name('change-password.show')
        ->breadcrumb('Change Password', 'user.user-settings.show')
        ->withoutMiddleware([CheckPasswordExpire::class]);

    Route::get('remove-device/{user}/{device}', RemoveDeviceController::class)
        ->name('remove-device');

    Route::post('pin-link-item', PinnedLinksController::class)->name('pinned-links');
});

/**
 * Routes for Initializing a users account/first time setup
 */
Route::middleware('guest')->group(function () {
    Route::get('initialize-account/{token}', [InitializeUserController::class, 'show'])
        ->name('initialize');
    Route::put('initialize-account/{token}', [InitializeUserController::class, 'update'])
        ->name('initialize.update');
});
