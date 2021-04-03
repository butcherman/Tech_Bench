<?php

use App\Http\Controllers\Admin\AdminHomeController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Guest\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\User\DisabledUserController;
use App\Http\Controllers\User\ListActiveUsersController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserEmailNotificationsController;
use App\Http\Controllers\User\UserInitializeController;
use App\Http\Controllers\User\UserRolesController;
use App\Models\UserInitialize;

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
    Route::get('/finish-setup/{token}', [UserInitializeController::class, 'show'])->name('initialize');
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
*   Administration Routes
*/
Route::middleware('auth')->prefix('administration')->name('admin.')->group(function () {
    Route::get('/', AdminHomeController::class)->name('index');

    //  User Administration Routes
    Route::get(   '/create-user',            [UserController::class, 'create'])       ->name('user.create');
    Route::post(  '/create-user',            [UserController::class, 'store'])        ->name('user.store');
    Route::get(   '/modify-user',             ListActiveUsersController::class)       ->name('user.list');
    Route::get(   '/modify-user/{username}', [UserController::class, 'edit'])         ->name('user.edit');
    Route::put(   '/modify-user/{setting}',  [UserController::class, 'update'])       ->name('user.update');
    Route::delete('/modify-user/{username}', [UserController::class, 'destroy'])      ->name('user.destroy');
    Route::get(   '/disabled-users',         [DisabledUserController::class, 'index'])->name('disabled.index');
    Route::put(   '/disabled-users/{id}',    [DisabledUserController::class, 'update'])->name('disabled.update');

    Route::resource('user-roles',             UserRolesController::class);
});

