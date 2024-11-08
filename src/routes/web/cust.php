<?php

use App\Http\Controllers\Customer\CustomerAdministrationController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| Customer Based Routes
|-------------------------------------------------------------------------------
*/

Route::middleware('auth.secure')->group(function () {
    Route::prefix('customers')->name('customers.')->group(function () {

        /*
        |-----------------------------------------------------------------------
        | Customer Administration
        |-----------------------------------------------------------------------
        */

        Route::get('settings', [CustomerAdministrationController::class, 'edit'])
            ->name('settings.edit')
            ->breadcrumb('Customer Settings', 'admin.index');
        Route::put('settings', [CustomerAdministrationController::class, 'update'])
            ->name('settings.update');
    });
});
