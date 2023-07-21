<?php

use App\Http\Controllers\User\InitializeUserController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\NotificationSettingsController;
use App\Http\Controllers\User\UserPasswordController;
use App\Http\Controllers\User\UserSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::middleware('user_security')->prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [UserSettingsController::class, 'get'])->name('index')->breadcrumb('Settings', 'dashboard');
        Route::post('{user}', [UserSettingsController::class, 'set'])->name('set');
        Route::post('notifications/{user}', NotificationSettingsController::class)->name('notifications');
    });
    Route::post('notifications', NotificationController::class)->name('notifications');
    Route::get('password', UserPasswordController::class)->name('password')->breadcrumb('Change Password', 'user.settings.index');
});

Route::middleware('guest')->group(function () {
    Route::get('initialize-account/{token}', [InitializeUserController::class, 'get'])->name('initialize');
    Route::post('initialize-account/{token}', [InitializeUserController::class, 'set'])->name('initialize.submit');
});
