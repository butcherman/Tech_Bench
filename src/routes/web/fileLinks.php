<?php

use App\Http\Controllers\FileLink\ExpireFileLinkController;
use App\Http\Controllers\FileLink\FileLinkController;
use App\Http\Controllers\FileLink\UploadFileController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.secure')->group(function () {
    Route::post('links/upload', UploadFileController::class)
        ->name('links.upload');
    Route::get('expire/{link}', ExpireFileLinkController::class)
        ->name('links.expire');

    Route::resource('links', FileLinkController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('File Links')
                ->create('New File Link');
        });
});