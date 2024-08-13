<?php

use App\Http\Controllers\FileLink\ExpireFileLinkController;
use App\Http\Controllers\FileLink\ExtendLinkController;
use App\Http\Controllers\FileLink\FileLinkController;
use App\Http\Controllers\FileLink\UploadFileController;
use App\Models\FileLink;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.secure')->group(function () {
    Route::post('links/upload', UploadFileController::class)
        ->name('links.upload');
    Route::get('{link}/expire', ExpireFileLinkController::class)
        ->name('links.expire');
    Route::get('{link}/extend', ExtendLinkController::class)
        ->name('links.extend');

    Route::resource('links', FileLinkController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('File Links')
                ->create('New File Link')
                ->show('Link Details');
        });

});

Route::get('file-links/{link}', function ($link) {
    return $link;
})->name('guest-link.show');