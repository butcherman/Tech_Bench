<?php

use Illuminate\Support\Facades\Route;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;

use App\Http\Controllers\Equipment\EquipmentController;
use App\Http\Controllers\Equipment\DataTypesController;
use App\Http\Controllers\Equipment\EquipmentCategoryController;

/**
 * Equipment Routes
 */
Route::middleware('auth')->group(function()
{
    Route::resource('equipment', EquipmentController::class)->breadcrumbs(function(ResourceBreadcrumbs $breadcrumbs) {
        $breadcrumbs->index('Equipment Categories & Types', 'admin.index');
        $breadcrumbs->edit('Edit Equipment', '.index');
    })->except(['create']);
    //  Override the resource create method
    Route::get('equipment/create/{equipmentCategory:name}', [EquipmentController::class, 'create'])
        ->name('equipment.create')
        ->breadcrumb('New Equipment', 'equipment.index');

    Route::resource('equipment-categories', EquipmentCategoryController::class)->breadcrumbs(function(ResourceBreadcrumbs $breadcrumbs) {
        $breadcrumbs->create('Create Category', 'equipment.index');
        // $breadcrumbs->edit('Modify Category', 'equipment.index');  TODO - why does this trigger error?
    });

    Route::resource('data-types', DataTypesController::class)->breadcrumbs(function(ResourceBreadcrumbs $breadcrumbs) {
        $breadcrumbs->index('Equipment Data Types', 'equipment.index');
        // $breadcrumbs->show('References', 'equipment.index');   //  TODO - Why does this trigger error???
    });
});
