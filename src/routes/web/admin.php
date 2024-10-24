<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Config\BasicSettingsController;
use App\Http\Controllers\Admin\Config\EmailSettingsController;
use App\Http\Controllers\Admin\Config\FeatureController;
use App\Http\Controllers\Admin\Config\LogoController;
use App\Http\Controllers\Admin\Config\SecurityController;
use App\Http\Controllers\Admin\Config\SendTestEmailController;
use App\Http\Controllers\Admin\User\DeactivatedUserController;
use App\Http\Controllers\Admin\User\PasswordPolicyController;
use App\Http\Controllers\Admin\User\SendWelcomeEmailController;
use App\Http\Controllers\Admin\User\UserAdministrationController;
use App\Http\Controllers\Admin\User\UserRolesController;
use App\Http\Controllers\Admin\User\UserSettingsController;
use App\Http\Controllers\Home\FileTypesController;
use App\Http\Controllers\Home\PhoneTypesController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;

Route::middleware('auth.secure')->prefix('administration')->name('admin.')->group(function () {
    /***************************************************************************
     * Administration Home Page
     ***************************************************************************/
    Route::get('/', AdminController::class)->name('index')
        ->breadcrumb('Administration');

    /***************************************************************************
     * User Administration
     ***************************************************************************/
    Route::prefix('users')->name('user.')->group(function () {
        Route::get('user-settings', [UserSettingsController::class, 'show'])
            ->name('user-settings.show')
            ->breadcrumb('User Settings', 'admin.index');
        Route::put('user-settings', [UserSettingsController::class, 'update'])
            ->name('user-settings.update');
        Route::get('password-policy', [PasswordPolicyController::class, 'show'])
            ->name('password-policy.show')
            ->breadcrumb('Password Policy', 'admin.index');
        Route::put('password-policy', [PasswordPolicyController::class, 'update'])
            ->name('password-policy.update');
        Route::get('deactivated-users', DeactivatedUserController::class)
            ->name('deactivated')
            ->breadcrumb('Disabled Users', 'admin.user.index');
        Route::post('send-reset-password-link', [PasswordResetLinkController::class, 'store'])
            ->name('password-link');
        Route::get('{user}/restore', [UserAdministrationController::class, 'restore'])
            ->withTrashed()
            ->name('restore');
        Route::get('{user}/resend-welcome-email', SendWelcomeEmailController::class)
            ->name('send-welcome');
    });
    Route::resource('user', UserAdministrationController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('User Administration', 'admin.index')
                ->create('New User', 'admin.user.index')
                ->show('User Details', 'admin.user.index')
                ->edit('Edit User Details', 'admin.user.show');
        })->withTrashed();

    /***************************************************************************
     * User Role Administration
     ***************************************************************************/
    Route::post('user-roles/create', [UserRolesController::class, 'create'])
        ->name('user-roles.copy')
        ->breadcrumb('Build New Role', 'admin.user-roles.index');
    Route::resource('user-roles', UserRolesController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Roles and Permissions', 'admin.index')
                ->create('Build New Role')
                ->show('View Role')
                ->edit('Modify Role');
        });

    /***************************************************************************
     * Additional Routes for Customer Administration
     ***************************************************************************/
    Route::resource('file-types', FileTypesController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Customer File Types', 'customers.settings.edit');
        })->except(['edit', 'show']);
    Route::resource('phone-types', PhoneTypesController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Customer Contact Phone Types', 'customers.settings.edit');
        })->except(['edit', 'show']);

    /***************************************************************************
     * Application Configuration Routes
     ***************************************************************************/
    Route::get('logo', [LogoController::class, 'show'])
        ->name('logo.show')
        ->breadcrumb('Tech Bench Logo', 'admin.index');
    Route::post('logo', [LogoController::class, 'update'])
        ->name('logo.update');

    Route::get('basic-settings', [BasicSettingsController::class, 'show'])
        ->name('basic-settings.show')
        ->breadcrumb('Tech Bench Settings', 'admin.index');
    Route::put('basic-settings', [BasicSettingsController::class, 'update'])
        ->name('basic-settings.update');

    Route::get('email-settings', [EmailSettingsController::class, 'show'])
        ->name('email-settings.show')
        ->breadcrumb('Email Settings', 'admin.index');
    Route::put('email-settings', [EmailSettingsController::class, 'update'])
        ->name('email-settings.update');
    Route::get('test-email', SendTestEmailController::class)->name('test-email');

    Route::get('features', [FeatureController::class, 'show'])
        ->name('features.show')
        ->breadcrumb('App Features', 'admin.index');
    Route::put('features', [FeatureController::class, 'update'])
        ->name('features.update');

    Route::resource('security', SecurityController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('SSL Certificate', 'admin.index')
                ->create('Upload New Certificate')
                ->edit('Generate CSR', 'admin.security.index');
        })->except(['show']);
});
