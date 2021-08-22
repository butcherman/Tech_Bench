<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Home\DashboardController;

/*
*   Standard Routes for users that have been successfully Authenticated
*/
Route::middleware('auth')->group(function()
{
    Route::get('/dashboard', DashboardController::class)->name('dashboard');




    Route::get('/edit-password', function()
    {
        return 'blah';
    })->name('password.edit');



    Route::get('/kill-session', function()
    {
        Auth::logout();
        session()->flush();

        return redirect(route('home'));
    });
});
