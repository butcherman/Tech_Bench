<?php

/**
 *          Note for all routes:
 *              Some Resource Routes have been broken out into their individual routes.  This is
 *              because the Gretel Breadcrumb package relies on Route Model Binding which we are not
 *              doing at this time.
 *              This may be changed later.
 */

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\Home\DownloadController;
use App\Http\Controllers\Home\NotificationController;
use App\Http\Controllers\Home\UploadFileController;
use App\Http\Controllers\Home\UploadImageController;
use App\Http\Controllers\User\UserSettingsController;
use App\Http\Controllers\User\ChangePasswordController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;

/**
*   Standard Routes for users that have been successfully Authenticated
*/
Route::middleware('auth')->group(function()
{
    Route::get('dashboard',          DashboardController::class)->name('dashboard')->breadcrumb('Dashboard');
    Route::get('about',              AboutController::class)    ->name('about')    ->breadcrumb('About Tech Bench', 'dashboard');

    // Route::post('notifications',     NotificationController::class)->name('notifications');
    Route::resource('settings',      UserSettingsController::class)->breadcrumbs(function(ResourceBreadcrumbs $breadcrumbs)
    {
        $breadcrumbs->index('User Settings');
    });
    Route::resource('password',      ChangePasswordController::class)->breadcrumbs(function(ResourceBreadcrumbs $breadcrumbs)
    {
        $breadcrumbs->index('Change Password', 'settings.index');
    });

    // Route::post('upload-image',      UploadImageController::class)->name('upload-image');
});

/**
 * Standard routes for both Authenticated users and guests
 */
// Route::post('upload-file',            UploadFileController::class) ->name('upload-file');
// Route::get('download/{id}/{name}',    DownloadController::class)->name('download');
