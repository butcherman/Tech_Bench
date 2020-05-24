<?php
/*
*   Basic User Authenticated Routes
*/
Route::middleware('auth')->group(function()
{
    //  Basic non-logic routes
    Route::view('/about', 'about')->name('about');





    // Route::post('/logout')
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');







    Route::get('/change-password', function()
    {
        return response('change password');
    })->name('change_password');

});
