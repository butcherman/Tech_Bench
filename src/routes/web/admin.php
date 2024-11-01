<?php

use App\Http\Controllers\Admin\AdministrationController;
use App\Http\Controllers\Admin\User\PasswordPolicyController;
use App\Http\Controllers\Admin\User\UserSettingsController;
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
});
