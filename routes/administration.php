<?php
/*
*   System Administration Routes
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function()
{






    Route::get('check-user/{name}/{type}',    'Admin\UserController@checkUser')->name('check_user');



    Route::view('/', 'admin.index')->middleware('can:allow_admin')->name('index');
});
