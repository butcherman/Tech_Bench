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
        Route::middleware('check_role:Manage Users')->group(function()
        {
            Route::get('login-history/{id}/{name}', 'Admin\UserController@loginHistory')  ->name('login_history');
            Route::get('edit/{id}',                 'Admin\UserController@edit')          ->name('edit');
            Route::get('active-users',              'Admin\UserController@listActive')    ->name('active_users');
            Route::get('create',                    'Admin\UserController@create')        ->name('create');
            Route::post('create',                   'Admin\UserController@store')         ->name('store');
            Route::post('edit/{id}',                'Admin\UserController@update')        ->name('update');
            Route::post('change-password',          'Admin\UserController@changePassword')->name('change_password');
            Route::delete('deactivate/{id}',        'Admin\UserController@destroy')       ->name('destroy');
        });
        Route::get('check/{name}/{type}', 'Admin\UserController@checkUser')->name('check');
    });






    Route::view('/', 'admin.index')->middleware('can:allow_admin')->name('index');
});
