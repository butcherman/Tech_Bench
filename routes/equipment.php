<?php

use App\Http\Controllers\Equipment\DataTypesController;
use App\Http\Controllers\Equipment\EquipmentCategoryController;
use App\Http\Controllers\Equipment\EquipmentController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'user_security'])->group(function () {
    Route::resource('equipment', EquipmentController::class)->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
        $breadcrumbs->index('Equipment Administration', 'admin.index')
            ->create('Create New Equipment', '.index')
            ->show('Equipment References', '.edit')
            ->edit('Edit Equipment', '.index');
    });

    Route::resource('equipment-category', EquipmentCategoryController::class);

    Route::resource('data-types', DataTypesController::class)->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
        $breadcrumbs->index('Equipment Data Types', 'equipment.index')
            ->create('Create New Data Type', '.index')
            ->edit('Update Data Type', '.index');
    });
});
