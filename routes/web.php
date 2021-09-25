<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\Home\UploadFileController;
use App\Http\Controllers\Home\UploadImageController;
use App\Http\Controllers\User\UserSettingsController;
use App\Http\Controllers\User\ChangePasswordController;

/*
*   Standard Routes for users that have been successfully Authenticated
*/
Route::middleware('auth')->group(function()
{
    Route::get('dashboard',     DashboardController::class)->name('dashboard');
    Route::get('about',         AboutController::class)    ->name('about');

    Route::resource('settings', UserSettingsController::class);
    Route::resource('password', ChangePasswordController::class);

    Route::post('upload-image', UploadImageController::class)->name('upload-image');
    Route::post('upload-file',  UploadFileController::class) ->name('upload-file');

    Route::get('download/{id}/{name}', function()
    {
        return 'download file';
    })->name('download');
});
