<?php
/*
*   Customer Routes
*/



Route::middleware('auth')->prefix('customer')->name('customer.')->group(function()
{
    Route::delete('disable/{id}', 'Customers\CustomerController@delete')->name('destroy')->middleware('check_role:Deactivate Customer');

    Route::view('new',            'customers.create')->name('create');
    Route::post('new',            'Customers\CustomerController@store')->name('store');

    Route::put('update/{id}',     'Customers\CustomerController@update')->name('update');
    Route::get('link-parent',     'Customers\CustomerController@linkParent')->name('link_parent');
    Route::get('toggle-fav/{id}', 'Customers\CustomerController@toggleFav')->name('toggle_fav');
    Route::get('check-id/{id}',   'Customers\CustomerController@checkID')->name('check_id');
    Route::get('{id}/{name}',     'Customers\CustomerController@details')->name('details');
    Route::get('search',          'Customers\CustomerController@search')->name('search');
});


Route::get('customers', 'Customers\CustomerController@index')->middleware('auth')->name('customer.index');
