<?php

use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Customers\CustomerSearchController;
use Illuminate\Support\Facades\Route;

/**
 * Customer Information Routes
 */
Route::middleware('auth')->group(function()
{
    Route::resource('customers', CustomerController::class);

    Route::prefix('customers')->name('customers.')->group(function()
    {
        Route::post('search', CustomerSearchController::class)->name('search');
    });
});
