<?php

use App\Http\Controllers\Admin\AdministrationController;
use App\Http\Controllers\Admin\Config\BasicSettingsController;
use App\Http\Controllers\Admin\Config\EmailSettingsController;
use App\Http\Controllers\Admin\Config\FeatureController;
use App\Http\Controllers\Admin\Config\LogoController;
use App\Http\Controllers\Admin\Config\SecurityController;
use App\Http\Controllers\Admin\Config\SendTestEmailController;
use App\Http\Controllers\Admin\User\PasswordPolicyController;
use App\Http\Controllers\Admin\User\UserSettingsController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| System Administration Routes
|-------------------------------------------------------------------------------
*/

Route::middleware('auth.secure')->prefix('administration')->name('admin.')->group(function () {
    Route::get('/', AdministrationController::class)
        ->name('index')
        ->breadcrumb('Administration');

    /*
    |---------------------------------------------------------------------------
    | User Settings Administration
    |---------------------------------------------------------------------------
    */
    Route::prefix('users')->name('user.')->group(function () {
        Route::get('password-policy', [PasswordPolicyController::class, 'edit'])
            ->name('password-policy.edit')
            ->breadcrumb('Password Policy', 'admin.index');
        Route::put('password-policy', [PasswordPolicyController::class, 'update'])
            ->name('password-policy.update');

        Route::get('user-settings', [UserSettingsController::class, 'edit'])
            ->name('user-settings.edit')
            ->breadcrumb('User Settings', 'admin.index');
        Route::put('user-settings', [UserSettingsController::class, 'update'])
            ->name('user-settings.update');
    });

    /*
    |---------------------------------------------------------------------------
    | Application Configuration
    |---------------------------------------------------------------------------
    */
    Route::get('logo', [LogoController::class, 'edit'])
        ->name('logo.edit')
        ->breadcrumb('Tech Bench Logo', 'admin.index');
    Route::post('logo', [LogoController::class, 'update'])
        ->name('logo.update');

    Route::get('basic-settings', [BasicSettingsController::class, 'edit'])
        ->name('basic-settings.edit')
        ->breadcrumb('Tech Bench Settings', 'admin.index');
    Route::put('basic-settings', [BasicSettingsController::class, 'update'])
        ->name('basic-settings.update');

    Route::get('email-settings', [EmailSettingsController::class, 'edit'])
        ->name('email-settings.edit')
        ->breadcrumb('Email Settings', 'admin.index');
    Route::put('email-settings', [EmailSettingsController::class, 'update'])
        ->name('email-settings.update');
    Route::get('test-email', SendTestEmailController::class)
        ->name('test-email');

    Route::get('features', [FeatureController::class, 'edit'])
        ->name('features.edit')
        ->breadcrumb('App Features', 'admin.index');
    Route::put('features', [FeatureController::class, 'update'])
        ->name('features.update');

    Route::resource('security', SecurityController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('SSL Certificate', 'admin.index')
                ->create('Upload New Certificate')
                ->edit('Generate CSR', 'admin.security.index');
        })->except(['edit']);
});
