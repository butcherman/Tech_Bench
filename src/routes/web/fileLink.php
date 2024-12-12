<?php

use App\Exceptions\FileLink\FileLinkMissingException;
use App\Http\Controllers\FileLink\ExpireFileLinkController;
use App\Http\Controllers\FileLink\ExtendFileLinkController;
use App\Http\Controllers\FileLink\FileLinkAdministrationController;
use App\Http\Controllers\FileLink\FileLinkController;
use App\Http\Controllers\FileLink\FileLinkFileController;
use App\Http\Controllers\FileLink\FileLinkSettingsController;
use App\Http\Controllers\Public\PublicFileLinkController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Routes for File Link Usage
 */
Route::middleware('auth.secure')->group(function () {
    Route::get('{link}/expire', ExpireFileLinkController::class)
        ->name('links.expire');
    Route::get('{link}/extend', ExtendFileLinkController::class)
        ->name('links.extend');

    // File Link Files
    Route::controller(FileLinkFileController::class)->group(function () {
        Route::post('{link}/add-file', 'store')->name('links.add-file');
        Route::delete('{link}/delete-file/{linkFile}', 'destroy')
            ->name('links.destroy-file');
    });

    Route::resource('links', FileLinkController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('File Links')
                ->create('New File Link')
                ->show('Link Details')
                ->edit('Edit Link');
        });

    /*
    |---------------------------------------------------------------------------
    | File Link Administration
    | /administration/file-links
    |---------------------------------------------------------------------------
    */
    Route::prefix('administration/file-links')->name('admin.links.')->group(function () {
        /*
        |-----------------------------------------------------------------------
        | File Link Settings
        | /administration/file-links/settings
        |-----------------------------------------------------------------------
        */
        Route::controller(FileLinkSettingsController::class)
            ->name('settings.')
            ->group(function () {
                Route::get('settings', 'edit')
                    ->name('edit')
                    ->breadcrumb('File Link Settings', 'admin.index');
                Route::put('settings', 'update')->name('update');
            });

        Route::controller(FileLinkAdministrationController::class)
            ->name('manage.')
            ->group(function () {
                Route::get('manage', 'index')
                    ->name('index')
                    ->breadcrumb('Manage File Links', 'admin.index');
                Route::get('manage/{link}', 'show')
                    ->name('show')
                    ->breadcrumb('Link Details', 'admin.links.manage.index');
                Route::delete('manage/{link}', 'destroy')->name('destroy');
            });
    });
});

/**
 * Guest Routes
 */
// Route::get('file-links/{link:link_hash}', [PublicFileLinkController::class, 'show'])
//     ->name('guest-link.show')
//     ->missing(function (Request $request) {
//         throw new FileLinkMissingException($request);
//     });
Route::get('fil-links/{public}', function () {
    return 'guest link';
})->name('guest-link.show');
// Route::post('file-links/{link:link_hash}', [PublicFileLinkController::class, 'update'])
//     ->name('guest-link.update');
