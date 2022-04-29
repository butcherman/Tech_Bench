<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Equipment\EquipmentController;
use App\Http\Controllers\Equipment\DataTypesController;
use App\Http\Controllers\Equipment\ListEquipmentController;
use App\Http\Controllers\Equipment\EquipmentCategoryController;

/**
 * Equipment Routes
 */
Route::middleware('auth')->group(function()
{
    Route::get('equipment-list',            ListEquipmentController::class)->name('list-equipment');

    Route::prefix('equipment')->name('equipment.')->group(function()
    {
        Route::get(   '/',                [EquipmentController::class, 'index'])  ->name('index')->breadcrumb('Equipment', 'admin.index');
        Route::post(  '/',                [EquipmentController::class, 'store'])  ->name('store');
        Route::get(   '{equipment}',      [EquipmentController::class, 'show'])   ->name('show')->breadcrumb('New Equipment', '.index');
        Route::get(   '{equipment}/edit', [EquipmentController::class, 'edit'])   ->name('edit')->breadcrumb('Edit Equipment', '.index');
        Route::put(   '{equipment}/edit', [EquipmentController::class, 'update']) ->name('update');
        Route::delete('{equipment}',      [EquipmentController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('data-types')->name('data-types.')->group(function()
    {
        Route::get(   '/',                [DataTypesController::class, 'index'])  ->name('index')->breadcrumb('Equipment Data Types', 'equipment.index');
        Route::post(  '/',                [DataTypesController::class, 'store'])  ->name('store');
        Route::put(   '{type}',           [DataTypesController::class, 'update']) ->name('update');
        Route::delete('{type}',           [DataTypesController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('equipment-categories')->name('equipment-categories.')->group(function()
    {
        Route::get(   'create',          [EquipmentCategoryController::class, 'create']) ->name('create')->breadcrumb('New Category', 'equipment.index');
        Route::post(  '/',               [EquipmentCategoryController::class, 'store'])  ->name('store');
        Route::get(   '{category}/edit', [EquipmentCategoryController::class, 'edit'])   ->name('edit')  ->breadcrumb('Edit Category', 'equipment.index');
        Route::put(   '{category}/edit', [EquipmentCategoryController::class, 'update']) ->name('update');
        Route::delete('{category}',      [EquipmentCategoryController::class, 'destroy'])->name('destroy');
    });
});
