<?php

use App\Http\Controllers\Equipment\EquipmentCategoryController;
use App\Http\Controllers\Equipment\EquipmentDataTypeController;
use App\Http\Controllers\Equipment\EquipmentTypeController;
use App\Models\EquipmentType;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.secure')->group(function () {

    /*
    |---------------------------------------------------------------------------
    | Equipment Type Administration
    |---------------------------------------------------------------------------
    */

    Route::resource('equipment', EquipmentTypeController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Equipment Categories and Types', 'admin.index')
                ->create('Create New Equipment')
                ->edit('Edit Equipment', 'equipment.index')
                ->show(
                    fn (EquipmentType $equipment) => $equipment->name.' References',
                    'equipment.edit'
                );
        });

    /*
    |---------------------------------------------------------------------------
    | Equipment Category Administration
    |---------------------------------------------------------------------------
    */

    Route::resource('equipment-category', EquipmentCategoryController::class)
        ->only(['store', 'update', 'destroy']);

    /*
    |---------------------------------------------------------------------------
    | Equipment Data Administration
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
