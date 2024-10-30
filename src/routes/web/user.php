<?php

use App\Http\Controllers\Admin\User\UserAdministrationController;
use App\Http\Controllers\User\RemoveDeviceTokenController;
use App\Http\Controllers\User\UpdateUserAccountController;
use App\Http\Controllers\User\UpdateUserSettingsController;
use App\Http\Controllers\User\UserPasswordController;
use App\Http\Controllers\User\UserSettingsController;
use App\Http\Middleware\CheckPasswordExpiration;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.secure')->group(function () {

    /*
    |---------------------------------------------------------------------------
    | User Account Routes
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

        Route::delete('remove-device/{user}/{token}', RemoveDeviceTokenController::class)
            ->name('remove-device');
    });

    /*
    |---------------------------------------------------------------------------
    | User Administration Routes
    |---------------------------------------------------------------------------
    */
    Route::prefix('administration')->name('admin.')->group(function () {
        Route::prefix('users')->name('user.')->group(function () {
            Route::get('{user}/restore', [UserAdministrationController::class, 'restore'])
                ->name('restore')
                ->withTrashed();
        });

        Route::resource('user', UserAdministrationController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('User Administration', 'admin.index')
                    ->create('New User')
                    ->show('User Details')
                    ->edit('Edit User Details');
            });
    });
});

Route::middleware('guest')->group(function () {
    Route::get('initialize-account/{token}', function () {
        return 'user initialize route';
    })->name('initialize');
    // Route::get('initialize-account/{token}', [InitializeUserController::class, 'show'])
    //     ->name('initialize')
    //     ->missing(function () {
    //         abort(404, 'Cannot Find the Requested Page');
    //     });
    // Route::put('initialize-account/{token}', [InitializeUserController::class, 'update'])
    //     ->name('initialize.update');
});
