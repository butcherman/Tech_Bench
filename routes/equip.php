<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Equipment\ListEquipmentController;

/**
 * Equipment Routes
 */
Route::middleware('auth')->group(function()
{
    Route::get('equipment-list', ListEquipmentController::class)->name('list-equipment');
});
