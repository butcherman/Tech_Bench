<?php

use App\Http\Controllers\Admin\AdminIndexController;
use App\Http\Controllers\Admin\DeactivatedUserController;
use App\Http\Controllers\Admin\ReactivateUserController;
use App\Http\Controllers\Admin\UserRolesController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('administration')->name('admin.')->group(function()
{
    Route::get('/', AdminIndexController::class)->name('index');

    Route::get('deactivated-users', DeactivatedUserController::class)->name('deactivated-users');
    Route::get('{user}/activate',   ReactivateUserController::class) ->name('reactivate-user');

    Route::resource('user', UserController::class);
    Route::resource('user-roles', UserRolesController::class);

});
