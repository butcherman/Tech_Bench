<?php
/*
*   Built in Authorization Routes
*/
Auth::routes(['register' => false]);
Route::get('download/{id}/{name}', 'DownloadController@index')->name('download');

/*
*   Basic Non-User Routes
*/
Route::middleware('guest')->group(function()
{
    Route::view('logout', 'auth.logout')->name('logout_complete');
    Route::view('/', 'auth.login')->name('index');
});
