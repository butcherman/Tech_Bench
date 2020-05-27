<?php
/*
*   System Administration Routes
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function()
{





    /*
    *   User Administration
    */
    Route::prefix('user')->name('user.')->group(function()
    {
        // Route::middleware('can:hasAccess', 'Manage Users')->group(function()
        // {
            Route::post('create', 'Admin\UserController@store')->name('store');
            Route::get('create', 'Admin\UserController@create')->name('create');
        // });
        Route::get('check/{name}/{type}', 'Admin\UserController@checkUser')->name('check');
    });






    Route::view('/', 'admin.index')->middleware('can:allow_admin')->name('index');
});
