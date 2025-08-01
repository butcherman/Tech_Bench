<?php

use App\Http\Controllers\Equipment\EquipmentCategoryController;
use App\Http\Controllers\Equipment\EquipmentDataTypeController;
use App\Http\Controllers\Equipment\EquipmentTypeController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| Equipment Based Routes
|-------------------------------------------------------------------------------
*/

Route::middleware('auth.secure')->group(function () {
    /*
    |---------------------------------------------------------------------------
    | Equipment Type Administration
    | /equipment
    |---------------------------------------------------------------------------
    */
    Route::resource('equipment', EquipmentTypeController::class)
        ->except(['show'])
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Equipment Categories and Types', 'admin.index')
                ->create('Create New Equipment')
                ->edit('Edit Equipment', 'equipment.index');
        });

    /*
    |---------------------------------------------------------------------------
    | Equipment Category Administration
    | /equipment-category
    |---------------------------------------------------------------------------
    */
    Route::resource('equipment-category', EquipmentCategoryController::class)
        ->only(['store', 'update', 'destroy']);

    /*
    |---------------------------------------------------------------------------
    | Equipment Data Administration
    | /equipment-data
    |---------------------------------------------------------------------------
    */
    Route::resource('equipment-data', EquipmentDataTypeController::class)
        ->except('show')
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Equipment Data Types', 'equipment.index')
                ->create('Create Data Type', 'equipment-data.index')
                ->edit('Edit Data Type', 'equipment-data.index');
        });
});
