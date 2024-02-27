<?php


use App\Http\Controllers\Equipment\EquipmentCategoryController;
use App\Http\Controllers\Equipment\EquipmentListController;
use App\Http\Controllers\Equipment\EquipmentTypeController;
use App\Http\Controllers\Equipment\EquipmentDataTypeController;
use App\Models\EquipmentType;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.secure')->group(function () {
    Route::get('equipment-list', EquipmentListController::class)
        ->name('equipment-list');

    /***************************************************************************
     * Equipment Administration
     ***************************************************************************/
    Route::resource('equipment', EquipmentTypeController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Equipment Categories and Types', 'admin.index')
                ->create('Create New Equipment')
                ->edit('Edit Equipment', 'equipment.index')
                ->show(fn(EquipmentType $equipment) => $equipment->name . ' References', 'equipment.edit');
        });

    Route::post('equipment-category', [EquipmentCategoryController::class, 'store'])
        ->name('equipment-category.store');
    Route::put('equipment-category/{category}', [EquipmentCategoryController::class, 'update'])
        ->name('equipment-category.update');
    Route::delete('equipment-category/{category}', [EquipmentCategoryController::class, 'destroy'])
        ->name('equipment-category.destroy');

    Route::resource('equipment-data', EquipmentDataTypeController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Equipment Data Types', 'equipment.index');
        });
});