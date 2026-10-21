<?php

use App\Http\Controllers\Admin\AdminIndexController;
use App\Http\Controllers\Admin\ApplicationConfigurationController;
use App\Http\Controllers\Admin\EmailSettingsController;
use App\Http\Controllers\Admin\LogoController;
use App\Http\Controllers\Admin\SendTestEmailController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| System Administration Routes
| /administration
|-------------------------------------------------------------------------------
*/

Route::middleware('auth.secure')->prefix('administration')->name('admin.')->group(function () {
    Route::get('/', AdminIndexController::class)
        ->name('index')
        ->breadcrumb('Administration');

    /*
    |---------------------------------------------------------------------------
    | Application Logo
    | /administration/logo
    |---------------------------------------------------------------------------
    */
    Route::controller(LogoController::class)->group(function () {
        Route::get('logo', 'edit')
            ->name('logo.edit')
            ->breadcrumb('Logo', 'admin.index');
        Route::post('logo', 'update')
            ->name('logo.update');
        Route::delete('logo', 'destroy')
            ->name('logo.destroy');
    });

    /*
    |---------------------------------------------------------------------------
    | Basic App Settings
    | /administration/configuration
    |---------------------------------------------------------------------------
    */
    Route::controller(ApplicationConfigurationController::class)->group(function () {
        Route::get('configuration', 'edit')
            ->name('basic-settings.edit')
            ->breadcrumb('Application Configuration', 'admin.index');
        Route::put('configuration', 'update')
            ->name('basic-settings.update');
    });

    /*
    |---------------------------------------------------------------------------
    | Email Settings
    | /administration/email-settings
    |---------------------------------------------------------------------------
    */
    Route::controller(EmailSettingsController::class)->group(function () {
        Route::get('email-settings', 'edit')
            ->name('email-settings.edit')
            ->breadcrumb('Email Settings', 'admin.index');
        Route::put('email-settings', 'update')
            ->name('email-settings.update');
    });

    Route::put('test-email', SendTestEmailController::class)
        ->name('test-email');
});
