<?php

use App\Http\Controllers\Home\AboutController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\User\UserSettingsController;

/*
*   Standard Routes for users that have been successfully Authenticated
*/
Route::middleware('auth')->group(function()
{
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('about',     AboutController::class)    ->name('about');

    Route::resource('settings', UserSettingsController::class);





    Route::get('/edit-password', function()
    {
        return 'blah';
    })->name('password.edit');



});
