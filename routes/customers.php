<?php
/*
*   Customer Routes
*/



Route::middleware('auth')->prefix('customer')->name('customer.')->group(function()
{
    Route::view('new',          'customers.create')->name('create');
    Route::post('new',          'Customers\CustomerController@store')->name('store');

    Route::get('check-id/{id}', 'Customers\CustomerController@checkID')->name('check_id');
    Route::get('{id}/{name}',   'Customers\CustomerController@details')->name('details');
    Route::get('search',        'Customers\CustomerController@search')->name('search');
});


Route::get('customers', 'Customers\CustomerController@index')->middleware('auth')->name('customer.index');
