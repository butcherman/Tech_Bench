<?php
/*
*   System Administration Routes
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function()
{
    /*
    *   System Administration
    */
    Route::prefix('equipment')->name('equipment.')->middleware('check_role:Manage Equipment')->group(function()
    {
        Route::resource('types',              'Admin\EquipmentTypesController');
        Route::resource('categories',         'Admin\EquipmentCategoriesController');
        Route::delete('equipment-information/{id}', 'Admin\EquipmentInformationController@deleteField')->name('delete_field');
        Route::post('equipmment-information', 'Admin\EquipmentInformationController@newField')->name('new_field');
        Route::put('equipmment-information', 'Admin\EquipmentInformationController@submitFieldName')->name('submit_field');
        Route::get('equipment-information',   'Admin\EquipmentInformationController@index')          ->name('equipment_information');
        Route::get('/',                       'Admin\EquipmentTypesController@index')                ->name('index');
    });




    /*
    *   User Administration
    */
    Route::prefix('user')->name('user.')->group(function()
    {
        Route::middleware('check_role:Manage Users')->group(function()
        {
            Route::view('password-policy',          'admin.passwordPolicy')                       ->name('password_policy');
            Route::get('login-history/{id}/{name}', 'Admin\UserController@loginHistory')          ->name('login_history');
            Route::get('edit/{id}',                 'Admin\UserController@edit')                  ->name('edit');
            Route::get('inactive-users',            'Admin\UserController@listInactive')          ->name('inactive_users');
            Route::get('active-users',              'Admin\UserController@listActive')            ->name('active_users');
            Route::get('activate/{id}',             'Admin\UserController@activate')              ->name('activate');
            Route::get('create',                    'Admin\UserController@create')                ->name('create');
            Route::post('create',                   'Admin\UserController@store')                 ->name('store');
            Route::post('edit/{id}',                'Admin\UserController@update')                ->name('update');
            Route::post('change-password',          'Admin\UserController@changePassword')        ->name('change_password');
            Route::post('password-policy',          'Admin\PasswordPolicyController@submitPolicy')->name('submit_password_policy');
            Route::delete('deactivate/{id}',        'Admin\UserController@destroy')               ->name('destroy');
        });
        Route::middleware('check_role:Manage Permissions')->group(function()
        {
            Route::get('manage-permissions',        'Admin\UserRoleController@permissionSettings')->name('permissions');
            Route::post('manage-permissions',       'Admin\UserRoleController@submitRole')->name('submit_role');
        });
        Route::get('check/{name}/{type}',           'Admin\UserController@checkUser')             ->name('check');
    });






    Route::view('/', 'admin.index')->middleware('can:allow_admin')->name('index');
});
