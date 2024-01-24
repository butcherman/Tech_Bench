<?php

use App\Http\Controllers\Admin\Config\BasicSettingsController;
use App\Http\Controllers\Admin\Config\EmailSettingsController;
use App\Http\Controllers\Admin\User\UserAdministrationController;
use Illuminate\Support\Facades\Route;

Route::prefix('administration')->name('admin.')->group(function () {

    Route::get('/', function () {
        return 'system administration';
    })->name('index');

    // Route::get('basic-settings', [BasicSettingsController::class, 'show'])
    //     ->name('basic-settings.show');
    // Route::put('basic-settings', [BasicSettingsController::class, 'update'])
    //     ->name('basic-settings.update');

    // Route::get('email-settings', [EmailSettingsController::class, 'show'])
    //     ->name('email-settings.show');
    // Route::put('email-settings', [EmailSettingsController::class, 'update'])
    //     ->name('email-settings.update');

    // Route::resource('users', UserAdministrationController::class);

});
