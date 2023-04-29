<?php

use App\Http\Controllers\Customers\CheckCustIdController;
use App\Http\Controllers\Customers\CustomerBookmarkController;
use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Customers\CustomerEquipmentController;
use App\Http\Controllers\Customers\CustomerFileTypeController;
use App\Http\Controllers\Customers\CustomerIdController;
use App\Http\Controllers\Customers\CustomerSearchController;
use App\Http\Controllers\Customers\DeactivatedCustomerController;
use App\Http\Controllers\Customers\GetCustomerSettingsController;
use App\Http\Controllers\Customers\GetDeletedItemsController;
use App\Http\Controllers\Customers\GetLinkedCustomerController;
use App\Http\Controllers\Customers\SetCustomerSettingsController;
use App\Http\Controllers\Customers\SetLinkedCustomerController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\Customers\CheckIdController;
// use App\Http\Controllers\Customers\GetLinkedController;
// use App\Http\Controllers\Customers\CustomerIdController;
// use App\Http\Controllers\Customers\CustomerNoteController;
// use App\Http\Controllers\Customers\LinkCustomerController;
// use App\Http\Controllers\Customers\CustomerFileController;
// use App\Http\Controllers\Customers\GetDeletedItemsController;
// use App\Http\Controllers\Customers\DownloadContactController;
// use App\Http\Controllers\Customers\CustomerBookmarkController;
// use App\Http\Controllers\Customers\CustomerContactsController;
// use App\Http\Controllers\Customers\CustomerEquipmentController;
// use App\Http\Controllers\Customers\CustomerFileTypesController;
// use App\Http\Controllers\Customers\DeactivatedCustomerController;

Route::middleware('auth')->group(function () {

    Route::prefix('customers')->name('customers.')->group(function () {
        Route::delete('force-delete', [CustomerController::class, 'forceDelete'])->name('force-delete');
        Route::post('restore', [CustomerController::class, 'restore'])->name('restore');
        Route::post('search', CustomerSearchController::class)->name('search');
        Route::post('bookmark', CustomerBookmarkController::class)->name('bookmark');
        Route::post('linked', SetLinkedCustomerController::class)->name('set-link');
        Route::get('{id}/check-id', CheckCustIdController::class)->name('check-id');
        Route::get('{customer}/get-linked', GetLinkedCustomerController::class)->name('linked');
        Route::get('{customer}/deleted-items', GetDeletedItemsController::class)->name('deleted-items');

        /**
         * Equipment
         */
        Route::resource('equipment', CustomerEquipmentController::class);
        Route::prefix('equipment')->name('equipment.')->group(function () {
            Route::get('{equipment}/restore', [CustomerEquipmentController::class, 'restore'])
                ->name('restore')
                ->withTrashed();
            Route::delete('{equipment}/force-delete', [CustomerEquipmentController::class, 'forceDelete'])
                ->name('force-delete')
                ->withTrashed();
        });
    });

    Route::resource('customers', CustomerController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Customers')
                ->create('New Customer')
                ->show('Customer Details');
        });

    /**
     * Customer Administration Routes
     */
    Route::prefix('administration/customers')->name('admin.cust.')->group(function () {
        Route::get('settings', GetCustomerSettingsController::class)
            ->name('settings')
            ->breadcrumb('Customer Settings', 'admin.index');
        Route::post('settings', SetCustomerSettingsController::class)
            ->name('set-settings');
        Route::get('deactivated-customers', DeactivatedCustomerController::class)
            ->name('deactivated')
            ->breadcrumb('Deactivated Customers', 'admin.cust.settings');
        Route::resource('change_id', CustomerIdController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Find Customer', 'admin.cust.settings')
                    ->show('Change ID');
            });
        Route::resource('file-types', CustomerFileTypeController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Customer File Types', 'admin.cust.settings');
            });
    });
});

/**
 * Customer Information Routes
 */
// Route::middleware('auth')->group(function()
// {
//     Route::prefix('customers')->name('customers.')->group(function()
//     {

//         Route::get( '{id}/get-deleted',         GetDeletedItemsController::class)                  ->name('get-deleted');
//         Route::get( '{id}/download-contact',    DownloadContactController::class)                  ->name('contacts.download');

//         //  Customer Resource Controllers
//         Route::resource('equipment',            CustomerEquipmentController::class);
//         Route::resource('contacts',             CustomerContactsController::class);
//         Route::resource('notes',                CustomerNoteController::class);
//         Route::resource('files',                CustomerFileController::class);

//         //  Additional Methods for Resource Controllers
//         Route::prefix('equipment')->name('equipment.')->group(function()
//         {
//             Route::get(   '{id}/restore',      [CustomerEquipmentController::class, 'restore'])    ->name('restore');
//             Route::delete('{id}/force-delete', [CustomerEquipmentController::class, 'forceDelete'])->name('force-delete');
//         });
//         Route::prefix('contacts')->name('contacts.')->group(function()
//         {
//             Route::get(   '{id}/restore',      [CustomerContactsController::class, 'restore'])     ->name('restore');
//             Route::delete('{id}/force-delete', [CustomerContactsController::class, 'forceDelete']) ->name('force-delete');
//         });
//         Route::prefix('notes')->name('notes.')->group(function()
//         {
//             Route::get(   '{id}/restore',      [CustomerNoteController::class, 'restore'])         ->name('restore');
//             Route::delete('{id}/force-delete', [CustomerNoteController::class, 'forceDelete'])     ->name('force-delete');
//         });
//         Route::prefix('files')->name('files.')->group(function()
//         {
//             Route::get(   '{id}/restore',      [CustomerFileController::class, 'restore'])         ->name('restore');
//             Route::delete('{id}/force-delete', [CustomerFileController::class, 'forceDelete'])     ->name('force-delete');
//         });
//     });
// });
