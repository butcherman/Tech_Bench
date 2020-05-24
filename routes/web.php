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
    Route::view('/', 'auth.login')->name('index');
    Route::view('logout', 'auth.logout')->name('logout_complete');
});
