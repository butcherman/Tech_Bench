<?php

use App\Http\Controllers\Equipment\DataTypesController;
use App\Http\Controllers\Equipment\EquipmentCategoryController;
use App\Http\Controllers\Equipment\EquipmentController;
use App\Http\Controllers\Equipment\GetEquipmentController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

/**
 * Equipment Routes
 */
Route::middleware('auth')->group(function () {
    Route::resource('equipment', EquipmentController::class)->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
        $breadcrumbs->index('Equipment Categories & Types', 'admin.index');
        $breadcrumbs->edit('Edit Equipment', '.index');
    })->except(['create']);
    //  Override the resource create method
    Route::get('equipment/create/{equipmentCategory:name}', [EquipmentController::class, 'create'])
        ->name('equipment.create')
        ->breadcrumb('New Equipment', 'equipment.index');

    Route::resource('equipment_categories', EquipmentCategoryController::class)->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
        $breadcrumbs->create('Create Category', 'equipment.index')
            ->edit('Edit Category', 'equipment.index');
    });

    Route::resource('data_types', DataTypesController::class)->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
        $breadcrumbs->index('Equipment Data Types', 'equipment.index')
            ->show('References', 'equipment.index');
    });

    // Route::get('get-equipment', GetEquipmentController::class)->name('equipment.get-all');
});
