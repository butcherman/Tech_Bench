<?php

use App\Exceptions\Auth\InitializeUserLinkMissingException;
use App\Http\Controllers\Admin\User\DisabledUserController;
use App\Http\Controllers\Admin\User\ResetUserPasswordController;
use App\Http\Controllers\Admin\User\UserAdministrationController;
use App\Http\Controllers\Admin\User\UserRolesController;
use App\Http\Controllers\User\InitializeUserController;
use App\Http\Controllers\User\RemoveDeviceTokenController;
use App\Http\Controllers\User\UpdateUserAccountController;
use App\Http\Controllers\User\UpdateUserSettingsController;
use App\Http\Controllers\User\UserPasswordController;
use App\Http\Controllers\User\UserSettingsController;
use App\Http\Middleware\CheckPasswordExpiration;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;

/*
|-------------------------------------------------------------------------------
| User Based Routes
|-------------------------------------------------------------------------------
*/

Route::middleware('auth.secure')->group(function () {

    /*
    |---------------------------------------------------------------------------
    | User Account Routes
    | /user
    |---------------------------------------------------------------------------
    */
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('change-password', UserPasswordController::class)
            ->name('change-password.show')
            ->breadcrumb('Change Password', 'user.user-settings.show')
            ->withoutMiddleware(CheckPasswordExpiration::class);

        Route::get('user-settings', UserSettingsController::class)
            ->name('user-settings.show')
            ->breadcrumb('User Settings', 'dashboard');

        Route::put('user-account/{user}', UpdateUserAccountController::class)
            ->name('user-account.update');

        Route::put('user-settings/{user}', UpdateUserSettingsController::class)
            ->name('user-settings.update');

        Route::delete(
            'remove-device/{user}/{token}',
            RemoveDeviceTokenController::class
        )->name('remove-device');
    });

    /*
    |---------------------------------------------------------------------------
    | User Administration Routes
    | /administration
    |---------------------------------------------------------------------------
    */
    Route::prefix('administration')->name('admin.')->group(function () {

        /*
        |-----------------------------------------------------------------------
        | User Account Administration
        | /administration/users
        |-----------------------------------------------------------------------
        */
        Route::prefix('users')->name('user.')->group(function () {
            Route::get('{user}/restore', [UserAdministrationController::class, 'restore'])
                ->name('restore')
                ->withTrashed();

            Route::post('send-reset-password-link', [PasswordResetLinkController::class, 'store'])
                ->name('password-link');

            Route::put('{user}/reset-user-password', ResetUserPasswordController::class)
                ->name('reset-password');

            Route::get('deactivated-users', DisabledUserController::class)
                ->name('deactivated')
                ->breadcrumb('Disabled Users', 'admin.user.index');
        });

        Route::resource('user', UserAdministrationController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('User Administration', 'admin.index')
                    ->create('New User')
                    ->show('User Details')
                    ->edit('Edit User Details');
            })->withTrashed();

        /*
        |-----------------------------------------------------------------------
        | User Roles Administration
        | /administration/user-roles
        |-----------------------------------------------------------------------
        */
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
    });
});

/*
|-------------------------------------------------------------------------------
| Finish Setting Up User Account Routes
|-------------------------------------------------------------------------------
*/
Route::middleware('guest')->controller(InitializeUserController::class)->group(function () {
    Route::get('initialize-account/{token}', 'show')
        ->name('initialize')
        ->missing(function () {
            throw new InitializeUserLinkMissingException;
        });
    Route::put('initialize-account/{token}', 'update')
        ->name('initialize.update')
        ->missing(function () {
            throw new InitializeUserLinkMissingException;
        });
});
