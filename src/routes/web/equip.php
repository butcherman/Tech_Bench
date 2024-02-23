<?php


use App\Http\Controllers\Equipment\EquipmentListController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.secure')->group(function () {
    Route::get('equipment-list', EquipmentListController::class)
        ->name('equipment-list');
});