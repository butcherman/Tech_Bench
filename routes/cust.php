<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Customers\CheckIdController;
use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Customers\GetLinkedController;
use App\Http\Controllers\Customers\LinkCustomerController;
use App\Http\Controllers\Customers\CustomerSearchController;
use App\Http\Controllers\Customers\GetDeletedItemsController;
use App\Http\Controllers\Customers\DownloadContactController;
use App\Http\Controllers\Customers\CustomerBookmarkController;
use App\Http\Controllers\Customers\CustomerContactsController;
use App\Http\Controllers\Customers\CustomerEquipmentController;
use App\Http\Controllers\Customers\CustomerFileController;
use App\Http\Controllers\Customers\CustomerFileTypesController;
use App\Http\Controllers\Customers\CustomerIdController;
use App\Http\Controllers\Customers\CustomerNoteController;
use App\Http\Controllers\Customers\DeactivatedCustomerController;

/**
 * Customer Information Routes
 */
Route::middleware('auth')->group(function()
{
    Route::resource('customers', CustomerController::class);

    Route::prefix('customers')->name('customers.')->group(function()
    {
        Route::post('force-delete',            [CustomerController::class, 'forceDelete'])->name('force-delete');
        Route::post('restore',                 [CustomerController::class, 'restore'])    ->name('restore');
        Route::post('search',                   CustomerSearchController::class)          ->name('search');
        Route::post('check-id',                 CheckIdController::class)                 ->name('check-id');
        Route::post('bookmark',                 CustomerBookmarkController::class)        ->name('bookmark');
        Route::post('link-customer',            LinkCustomerController::class)            ->name('link-customer');
        Route::get( '{id}/get-linked',          GetLinkedController::class)               ->name('get-linked');
        Route::get( '{id}/get-deleted',         GetDeletedItemsController::class)         ->name('get-deleted');
        Route::get( '{id}/download-contact',    DownloadContactController::class)         ->name('contacts.download');


        //  Customer Resource Controllers
        Route::resource('equipment',            CustomerEquipmentController::class);
        Route::resource('contacts',             CustomerContactsController::class);
        Route::resource('notes',                CustomerNoteController::class);
        Route::resource('files',                CustomerFileController::class);

        //  Additional Methods for Resource Controllers
        Route::prefix('equipment')->name('equipment.')->group(function()
        {
            Route::get(   '{id}/restore',      [CustomerEquipmentController::class, 'restore'])    ->name('restore');
            Route::delete('{id}/force-delete', [CustomerEquipmentController::class, 'forceDelete'])->name('force-delete');
        });
        Route::prefix('contacts')->name('contacts.')->group(function()
        {
            Route::get(   '{id}/restore',      [CustomerContactsController::class, 'restore'])    ->name('restore');
            Route::delete('{id}/force-delete', [CustomerContactsController::class, 'forceDelete'])->name('force-delete');
        });
        Route::prefix('notes')->name('notes.')->group(function()
        {
            Route::get(   '{id}/restore',      [CustomerNoteController::class, 'restore'])    ->name('restore');
            Route::delete('{id}/force-delete', [CustomerNoteController::class, 'forceDelete'])->name('force-delete');
        });
        Route::prefix('files')->name('files.')->group(function()
        {
            Route::get(   '{id}/restore',      [CustomerFileController::class, 'restore'])    ->name('restore');
            Route::delete('{id}/force-delete', [CustomerFileController::class, 'forceDelete'])->name('force-delete');
        });
    });

    //  Customer Administration Routes
    Route::prefix('administration/customers')->name('admin.cust.')->group(function()
    {
        Route::resource('file-types',           CustomerFileTypesController::class);
        Route::resource('change-id',            CustomerIdController::class);
        Route::get('/deactivated-customers',    DeactivatedCustomerController::class)->name('show-deactivated');
    });
});
