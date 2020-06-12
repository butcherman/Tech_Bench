<?php
/*
*   Customer Routes
*/



Route::middleware('auth')->prefix('customer')->name('customer.')->group(function()
{

    Route::get('{id}/{name}', 'Customers\CustomerController@details')->name('details');
    Route::get('search', 'Customers\CustomerController@search')->name('search');
});


Route::get('customers', 'Customers\CustomerController@index')->middleware('auth')->name('customer.index');
