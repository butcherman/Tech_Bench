<?php
/*
*   Built in Authorization Routes
*/
Auth::routes(['register' => false]);

/*
*   Basic Non-User Routes
*/
Route::middleware('guest')->group(function()
{
    Route::get('welcome', function()
    {
        return response('worked');
    })->name('initialize');



    Route::view('logout', 'auth.logout')->name('logout_complete');
    Route::view('/', 'auth.login')->name('index');
});
