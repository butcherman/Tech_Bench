<?php

/**
 *          Note for all routes:
 *              Some Resource Routes have been broken out into their individual routes.  This is
 *              because the Gretel Breadcrumb package relies on Route Model Binding which we are not
 *              doing at this time.
 *              This may be changed later.
 */

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\Home\DownloadController;
use App\Http\Controllers\Home\GetPhoneTypesController;
use App\Http\Controllers\Home\UploadFileController;
use App\Http\Controllers\Home\UploadImageController;
use App\Http\Controllers\User\ChangePasswordController;
use App\Http\Controllers\User\UpdateAccountController;
use App\Http\Controllers\User\UpdateNotificationsController;
use App\Http\Controllers\User\UserSettingsController;
use Illuminate\Support\Facades\Route;

/**
 *   Standard Routes for users that have been successfully Authenticated
 */
Route::middleware('auth')->group(function () {
    Route::get('dashboard', DashboardController::class)
        ->name('dashboard')
        ->breadcrumb('Dashboard');
    Route::get('about', AboutController::class)
        ->name('about')
        ->breadcrumb('About Tech Bench', 'dashboard');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', UserSettingsController::class)
            ->name('index')
            ->breadcrumb('Settings', 'dashboard');
        Route::get('password', [ChangePasswordController::class, 'get'])
            ->name('password.index')
            ->breadcrumb('Change Password', 'settings.index');

        Route::post('notifications', UpdateNotificationsController::class)->name('notifications');
        Route::post('/{user}/update', UpdateAccountController::class)->name('update');
        Route::post('password', [ChangePasswordController::class, 'set'])->name('password.store');
    });

    Route::get('phone-number-types', GetPhoneTypesController::class)->name('get-number-types');

    // Route::post('upload-image',      UploadImageController::class)->name('upload-image');
});

/**
 * Standard routes for both Authenticated users and guests
 */
// Route::post('upload-file',            UploadFileController::class) ->name('upload-file');
// Route::get('download/{id}/{name}',    DownloadController::class)->name('download');
