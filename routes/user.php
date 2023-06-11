<?php

use App\Http\Controllers\User\UserSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('settings', [UserSettingsController::class, 'get'])->name('index')->breadcrumb('Settings', 'dashboard');
        Route::post('settings/{user}', [UserSettingsController::class, 'set'])->name('set');
    });
});
