<?php

//  Login/Logout and Authorization routes
Auth::routes();

///////////////////////////  Basic Non user Routes  /////////////////////////////////////////
Route::get('/', 'Auth\LoginController@showLoginForm');





Route::get('dashboard', 'DashboardController@index')->name('dashboard');



