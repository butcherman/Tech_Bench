<?php

use App\Http\Controllers\API\GetEquipmentListController;
use App\Http\Controllers\API\GetPhoneTypesController;
use App\Http\Controllers\API\GetUploadFileTypesController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| Single Action Routes that return a JSON Resource (generally from cache).
|-------------------------------------------------------------------------------
*/
Route::middleware('auth.secure')->group(function () {
    Route::get('phone-types', GetPhoneTypesController::class)
        ->name('phone-types');

    Route::get('file-types', GetUploadFileTypesController::class)
        ->name('file-types');

    Route::get('equipment-list', GetEquipmentListController::class)
        ->name('equipment-list');
});
