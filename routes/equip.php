<?php

use App\Http\Controllers\Equipment\DataTypesController;
use App\Http\Controllers\Equipment\EquipmentCategoryController;
use App\Http\Controllers\Equipment\EquipmentController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Equipment\ListEquipmentController;

/**
 * Equipment Routes
 */
Route::middleware('auth')->group(function()
{
    Route::get('equipment-list',            ListEquipmentController::class)->name('list-equipment');

    Route::resource('equipment',            EquipmentController::class);
    Route::resource('data-types',           DataTypesController::class);
    Route::resource('equipment-categories', EquipmentCategoryController::class);
});
