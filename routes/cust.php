<?php

use App\Http\Controllers\Customers\CheckIdController;
use App\Http\Controllers\Customers\CustomerBookmarkController;
use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Customers\CustomerEquipmentController;
use App\Http\Controllers\Customers\CustomerSearchController;
use App\Http\Controllers\Customers\GetLinkedController;
use App\Http\Controllers\Customers\LinkCustomerController;
use Illuminate\Support\Facades\Route;

/**
 * Customer Information Routes
 */
Route::middleware('auth')->group(function()
{
    Route::resource('customers', CustomerController::class);

    Route::prefix('customers')->name('customers.')->group(function()
    {
        Route::post('search',          CustomerSearchController::class)  ->name('search');
        Route::post('check-id',        CheckIdController::class)         ->name('check-id');
        Route::post('bookmark',        CustomerBookmarkController::class)->name('bookmark');
        Route::post('link-customer',   LinkCustomerController::class)    ->name('link-customer');
        Route::get( '{id}/get-linked', GetLinkedController::class)       ->name('get-linked');

        Route::resource('equipment', CustomerEquipmentController::class);
    });
});
