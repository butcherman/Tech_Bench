<?php

use App\Exceptions\FileLink\FileLinkMissingException;
use App\Http\Controllers\FileLink\ExpireFileLinkController;
use App\Http\Controllers\FileLink\ExtendLinkController;
use App\Http\Controllers\FileLink\FileLinkAdminController;
use App\Http\Controllers\FileLink\FileLinkController;
use App\Http\Controllers\FileLink\FileLinkFileController;
use App\Http\Controllers\FileLink\FileLinkSettingsController;
use App\Http\Controllers\FileLink\UploadFileController;
use App\Http\Controllers\Public\PublicFileLinkController;
use App\Models\FileLink;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/**
 * Routes for File Link Usage
 */
Route::middleware('auth.secure')->group(function () {
    Route::post('links/upload', UploadFileController::class)
        ->name('links.upload');
    Route::get('{link}/expire', ExpireFileLinkController::class)
        ->name('links.expire');
    Route::get('{link}/extend', ExtendLinkController::class)
        ->name('links.extend');

    // File Link Files
    Route::post('{link}/add-file', [FileLinkFileController::class, 'store'])
        ->name('links.add-file');
    Route::delete('{link}/delete-file/{linkFile}', [FileLinkFileController::class, 'destroy'])
        ->name('links.destroy-file');

    Route::resource('links', FileLinkController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('File Links')
                ->create('New File Link')
                ->show('Link Details')
                ->edit('Edit Link');
        });

    /**
     * Routes for File Link Administration
     */
    Route::prefix('administration/file-links')->name('admin.links.')->group(function () {
        Route::get('settings', [FileLinkSettingsController::class, 'show'])
            ->name('settings.show')
            ->breadcrumb('File Link Settings', 'admin.index');
        Route::put('settings', [FileLinkSettingsController::class, 'update'])
            ->name('settings.update');

        Route::get('manage', [FileLinkAdminController::class, 'index'])
            ->name('manage.index')
            ->breadcrumb('Manage File Links');
        Route::get('manage/{link}', [FileLinkAdminController::class, 'show'])
            ->name('manage.show')
            ->breadcrumb('Link Details', 'admin.links.manage.index');
        Route::delete('manage/{link}', [FileLinkAdminController::class, 'destroy'])
            ->name('manage.destroy');
    });
});

/**
 * Guest Routes
 */
Route::get('file-links/{link:link_hash}', [PublicFileLinkController::class, 'show'])
    ->name('guest-link.show')
    ->missing(function (Request $request) {
        throw new FileLinkMissingException($request);
    });
Route::post('file-links/{link:link_hash}', [PublicFileLinkController::class, 'update'])
    ->name('guest-link.update');