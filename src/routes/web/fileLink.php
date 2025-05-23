<?php

use App\Http\Controllers\FileLink\ExpireLinkController;
use App\Http\Controllers\FileLink\FileLinkController;
use App\Http\Controllers\FileLink\FileLinkSettingsController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| File Link Based Routes
|-------------------------------------------------------------------------------
*/

Route::middleware('auth.secure')->group(function () {
    /*
    |---------------------------------------------------------------------------
    | File Links
    | /links
    |---------------------------------------------------------------------------
    */
    Route::get('links/{link}/expire', ExpireLinkController::class)
        ->name('links.expire');

    Route::resource('links', FileLinkController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('File Links')
                ->create('New File Link')
                ->show('Link Details')
                ->edit('Edit Link');
        });
});

Route::get('guest-link', function () {
    return 'guest link';
})->name('guest-link.show');

Route::get('admin-links', function () {
    return 'something admin';
})->name('admin.links.settings.edit');

Route::get('admin-adfa', function () {
    return 'something admin';
})->name('admin.links.manage.index');
