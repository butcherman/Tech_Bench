<?php

use App\Http\Controllers\Admin\AdminIndexController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('administration')->name('admin.')->group(function()
{
    Route::get('/', AdminIndexController::class)->name('index');

    Route::resource('user', UserController::class);
});
