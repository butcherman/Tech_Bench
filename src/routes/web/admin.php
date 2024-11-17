<?php

use App\Http\Controllers\Admin\AdministrationController;
use App\Http\Controllers\Admin\Config\BasicSettingsController;
use App\Http\Controllers\Admin\Config\EmailSettingsController;
use App\Http\Controllers\Admin\Config\FeatureController;
use App\Http\Controllers\Admin\Config\LogoController;
use App\Http\Controllers\Admin\Config\SecurityController;
use App\Http\Controllers\Admin\Config\SendTestEmailController;
use App\Http\Controllers\Admin\Misc\ContactPhoneTypesController;
use App\Http\Controllers\Admin\Misc\UploadedFileTypesController;
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
        /*
        |-----------------------------------------------------------------------
        | Password Policy
        |-----------------------------------------------------------------------
        */
        Route::controller(PasswordPolicyController::class)->group(function () {
            Route::get('password-policy', 'edit')
                ->name('password-policy.edit')
                ->breadcrumb('Password Policy', 'admin.index');
            Route::put('password-policy', 'update')
                ->name('password-policy.update');
        });

        /*
        |-----------------------------------------------------------------------
        | User Settings
        |-----------------------------------------------------------------------
        */
        Route::controller(UserSettingsController::class)->group(function () {
            Route::get('user-settings', 'edit')
                ->name('user-settings.edit')
                ->breadcrumb('User Settings', 'admin.index');
            Route::put('user-settings', 'update')
                ->name('user-settings.update');
        });
    });

    /*
    |---------------------------------------------------------------------------
    | Application Logo
    |---------------------------------------------------------------------------
    */
    Route::controller(LogoController::class)->group(function () {
        Route::get('logo', 'edit')
            ->name('logo.edit')
            ->breadcrumb('Tech Bench Logo', 'admin.index');
        Route::post('logo', 'update')
            ->name('logo.update');
    });

    /*
    |---------------------------------------------------------------------------
    | Basic App Settings
    |---------------------------------------------------------------------------
    */
    Route::controller(BasicSettingsController::class)->group(function () {
        Route::get('basic-settings', 'edit')
            ->name('basic-settings.edit')
            ->breadcrumb('Tech Bench Settings', 'admin.index');
        Route::put('basic-settings', 'update')
            ->name('basic-settings.update');
    });

    /*
    |---------------------------------------------------------------------------
    | Email Settings
    |---------------------------------------------------------------------------
    */
    Route::controller(EmailSettingsController::class)->group(function () {
        Route::get('email-settings', 'edit')
            ->name('email-settings.edit')
            ->breadcrumb('Email Settings', 'admin.index');
        Route::put('email-settings', 'update')
            ->name('email-settings.update');
    });
    Route::get('test-email', SendTestEmailController::class)
        ->name('test-email');

    /*
    |---------------------------------------------------------------------------
    | Feature Configuration
    |---------------------------------------------------------------------------
    */
    Route::controller(FeatureController::class)->group(function () {
        Route::get('features', 'edit')
            ->name('features.edit')
            ->breadcrumb('App Features', 'admin.index');
        Route::put('features', 'update')
            ->name('features.update');
    });

    /*
    |---------------------------------------------------------------------------
    | SSL Certificate Administration
    |---------------------------------------------------------------------------
    */
    Route::resource('security', SecurityController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('SSL Certificate', 'admin.index')
                ->create('Upload New Certificate')
                ->edit('Generate CSR', 'admin.security.index');
        })->except(['show']);

    /*
    |---------------------------------------------------------------------------
    | File Types for Uploaded Files
    |---------------------------------------------------------------------------
    */
    Route::resource('file-types', UploadedFileTypesController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Uploaded File Types', 'admin.index');
        })->except(['edit', 'show']);

    /*
    |---------------------------------------------------------------------------
    | Phone Number Types for Contacts
    |---------------------------------------------------------------------------
    */
    Route::resource('phone-types', ContactPhoneTypesController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Contact Phone Types', 'admin.index');
        })->except(['edit', 'show']);
});
