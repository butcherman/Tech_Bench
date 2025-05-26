<?php

use App\Exceptions\FileLink\FileLinkMissingException;
use App\Http\Controllers\FileLink\ExpireLinkController;
use App\Http\Controllers\FileLink\ExtendLinkController;
use App\Http\Controllers\FileLink\FileLinkController;
use App\Http\Controllers\FileLink\FileLinkFileController;
use App\Http\Controllers\FileLink\FileLinkSettingsController;
use App\Http\Controllers\FileLink\LinkAdministrationController;
use App\Http\Controllers\FileLink\MoveFileController;
use App\Http\Controllers\FileLink\PublicLinkController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| File Link Based Routes
|-------------------------------------------------------------------------------
*/

Route::middleware('auth.secure')->group(function () {
    /*
    |---------------------------------------------------------------------------
    | File Link Administration
    | /administration/file-links
    |---------------------------------------------------------------------------
    */
    Route::prefix('administration/file-links')->name('admin.links.')->group(function () {
        Route::controller(FileLinkSettingsController::class)
            ->name('settings.')
            ->group(function () {
                Route::get('settings', 'edit')->name('edit')
                    ->breadcrumb('File Link Settings', 'admin.index');
                Route::put('settings/update', 'update')->name('update');
            });

        Route::get('manage', LinkAdministrationController::class)
            ->name('manage.index')
            ->breadcrumb('Manage File Links', 'admin.index');
    });

    /*
    |---------------------------------------------------------------------------
    | File Links
    | /links
    |---------------------------------------------------------------------------
    */
    Route::prefix('links')->name('links.')->group(function () {
        Route::get('{link}/expire', ExpireLinkController::class)
            ->name('expire');
        Route::get('{link}/extend', ExtendLinkController::class)
            ->name('extend');

        Route::prefix('files/{link}')
            ->controller(FileLinkFileController::class)
            ->name('files.')
            ->group(function () {
                Route::post('store', 'store')->name('store');
                Route::put('{file}/update', 'update')->name('update');
                Route::delete('{file}/destroy', 'destroy')->name('destroy');
            });
    });

    Route::resource('links', FileLinkController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('File Links')
                ->create('New File Link')
                ->show('Link Details')
                ->edit('Edit Link');
        });
});

/*
|-------------------------------------------------------------------------------
| Public File Link Routes
| /file-links/{link-hash}
|-------------------------------------------------------------------------------
*/
Route::prefix('file-links/{link:link_hash}')
    ->controller(PublicLinkController::class)
    ->name('guest-link.')
    ->group(function () {
        Route::get('/', 'show')
            ->name('show')
            ->missing(fn() => throw new FileLinkMissingException());
        Route::post('/', 'update')
            ->name('update')
            ->missing(fn() => throw new FileLinkMissingException());
    })
;
