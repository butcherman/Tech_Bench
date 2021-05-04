<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminHomeController;

use App\Http\Controllers\Guest\HomeController;

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordController;

use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Customers\CheckCustIdController;
use App\Http\Controllers\Customers\CustomerSearchController;
use App\Http\Controllers\Customers\DownloadContactController;
use App\Http\Controllers\Customers\CustomerContactsController;
use App\Http\Controllers\Customers\CustomerEquipmentController;
use App\Http\Controllers\Customers\CustomerBookmarksController;
use App\Http\Controllers\Customers\CustomerFilesController;
use App\Http\Controllers\Customers\CustomerNoteController;
use App\Http\Controllers\Equip\EquipmentListController;
use App\Http\Controllers\Equip\EquipmentTypesController;
use App\Http\Controllers\Equip\EquipmentCategoriesController;

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserRolesController;
use App\Http\Controllers\User\DisabledUserController;
use App\Http\Controllers\User\UserInitializeController;
use App\Http\Controllers\User\ListActiveUsersController;
use App\Http\Controllers\User\UserEmailNotificationsController;

/*
*   Authentication Routes
*/
Route::middleware('guest')->group(function()
{
    //  Primary Login Routes
    Route::get( '/',                     HomeController::class) ->name('home');
    Route::get( '/login',                HomeController::class) ->name('login.index');
    Route::post('/login',                LoginController::class)->name('login.submit');

    //  Forgot password routes
    Route::get( '/forgot-password',     [PasswordController::class, 'index'])        ->name('password.forgot');
    Route::post('/forgot-password',     [PasswordController::class, 'store'])        ->name('password.store');
    Route::get( '/reset-password',      [PasswordController::class, 'resetPassword'])->name('password.reset');
    Route::put( '/reset-password',      [PasswordController::class, 'submitReset'])  ->name('password.reset');

    //  Finish setting up new user route
    Route::get('/finish-setup/{token}', [UserInitializeController::class, 'show'])  ->name('initialize');
    Route::put('/finish-setup/{token}', [UserInitializeController::class, 'update'])->name('initialize.update');
});

/*
*   Basic Authenticated User Routes
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/logout',    LogoutController::class)   ->name('logout');
    Route::get( '/dashboard', DashboardController::class)->name('dashboard');
    Route::get( '/about',     AboutController::class)    ->name('about');
});

/*
*   User Settings Routes
*/
Route::middleware('auth')->group(function () {
    //  Primary User Settings
    Route::resource('settings',            UserController::class);
    Route::resource('email-notifications', UserEmailNotificationsController::class);

    //  Change Password
    Route::get('password/{change}',       [PasswordController::class, 'edit'])  ->name('password.edit');
    Route::put('password/{id}',           [PasswordController::class, 'update'])->name('password.update');
});

/*
*   Customer Routes
*/
Route::middleware('auth')->group(function()
{
    Route::prefix('customers')->name('customers.')->group(function()
    {
        //  Customer Details Section
        Route::post('customers/check-id', CheckCustIdController::class)->name('check-id');
        Route::post('search',             CustomerSearchController::class)->name('search');
        Route::put('toggle-bookmark',     CustomerBookmarksController::class)->name('bookmark');

        //  Customer Equipment Section
        Route::get('equipment-list', EquipmentListController::class)->name('equip-list');
        Route::resource('equipment', CustomerEquipmentController::class);

        //  Customer Contacts Section
        Route::resource('contacts', CustomerContactsController::class);
        Route::get(     'download/{id}', DownloadContactController::class)->name('contacts.download');

        //  Customer Notes Section
        Route::resource('notes', CustomerNoteController::class);

        //  Customer Files Section
        Route::resource('files', CustomerFilesController::class);
    });

    //  Customer primary resource Routes
    Route::resource('customers',      CustomerController::class);
});

/*
*   Administration Routes
*/
Route::middleware('auth')->prefix('administration')->name('admin.')->group(function () {
    Route::get('/',                             AdminHomeController::class)->name('index');

    //  User Administration Routes
    Route::get(     '/create-user',            [UserController::class, 'create'])        ->name('user.create');
    Route::post(    '/create-user',            [UserController::class, 'store'])         ->name('user.store');
    Route::get(     '/modify-user',             ListActiveUsersController::class)        ->name('user.list');
    Route::get(     '/modify-user/{username}', [UserController::class, 'edit'])          ->name('user.edit');
    Route::put(     '/modify-user/{setting}',  [UserController::class, 'update'])        ->name('user.update');
    Route::delete(  '/modify-user/{username}', [UserController::class, 'destroy'])       ->name('user.destroy');
    Route::get(     '/disabled-users',         [DisabledUserController::class, 'index']) ->name('disabled.index');
    Route::put(     '/disabled-users/{id}',    [DisabledUserController::class, 'update'])->name('disabled.update');
    Route::resource('user-roles',               UserRolesController::class);

    //  Equipment Administration Routes
    Route::prefix(  'equipment')->name('equipment.')->group(function()
    {
        Route::resource('categories', EquipmentCategoriesController::class);
    });
    Route::resource('equipment',      EquipmentTypesController::class);
});

