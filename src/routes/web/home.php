<?php

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\Home\DownloadFileController;
use App\Http\Controllers\Home\FileTypesController;
use App\Http\Controllers\Home\NotificationController;
use App\Http\Controllers\Home\PhoneTypesController;
use App\Http\Controllers\Home\UploadImageController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.secure')->group(function () {
    Route::get('dashboard', DashboardController::class)
        ->name('dashboard')
        ->breadcrumb('Dashboard');
    Route::get('notifications', [NotificationController::class, 'index'])
        ->name('notifications.index')
        ->breadcrumb('Notifications', 'dashboard');
    Route::get('about', AboutController::class)
        ->name('about')
        ->breadcrumb('About', 'dashboard');
    Route::post('handle-notification', [NotificationController::class, 'update'])
        ->name('handle-notifications');
    Route::get('phone-types', [PhoneTypesController::class, 'create'])
        ->name('phone-types');
    Route::get('file-types', [FileTypesController::class, 'create'])
        ->name('file-types');
    Route::post('upload-image/{folder?}', UploadImageController::class)
        ->name('upload-image');
});

Route::get('download/{file}/{fileName}', DownloadFileController::class)
    ->name('download');
