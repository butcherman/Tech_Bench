<?php

use App\Http\Controllers\User\NotificationSettingsController;
use App\Http\Controllers\User\UserSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [UserSettingsController::class, 'get'])->name('index')->breadcrumb('Settings', 'dashboard');
        Route::post('{user}', [UserSettingsController::class, 'set'])->name('set');
        Route::post('notifications/{user}', NotificationSettingsController::class)->name('notifications');
    });
});
