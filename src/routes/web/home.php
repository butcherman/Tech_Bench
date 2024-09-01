<?php

use App\Enum\CrudAction;
use App\Events\TechTips\TechTipEvent;
use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\Home\DownloadFileController;
use App\Http\Controllers\Home\FileTypesController;
use App\Http\Controllers\Home\NotificationController;
use App\Http\Controllers\Home\PhoneTypesController;
use App\Http\Controllers\Home\UploadImageController;
use App\Models\TechTip;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.secure')->group(function () {
    Route::get('dashboard', DashboardController::class)
        ->name('dashboard')
        ->breadcrumb('Dashboard');
    Route::get('about', AboutController::class)
        ->name('about')
        ->breadcrumb('About', 'dashboard');
    Route::get('phone-types', [PhoneTypesController::class, 'create'])
        ->name('phone-types');
    Route::get('file-types', [FileTypesController::class, 'create'])
        ->name('file-types');
    Route::post('upload-image/{folder?}', UploadImageController::class)
        ->name('upload-image');

    Route::get('notification-test', function () {
        $tip = TechTip::find(15);
        event(new TechTipEvent($tip, CrudAction::Create, true));

        return back()->with('success', 'message sent');
    });
});

Route::get('download/{file}/{fileName}', DownloadFileController::class)
    ->name('download');
