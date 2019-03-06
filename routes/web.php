<?php

//  Login/Logout and Authorization routes
Auth::routes();

///////////////////////////  Basic Non user Routes  /////////////////////////////////////////
Route::get('/', 'Auth\LoginController@showLoginForm');




Route::get('logout', 'Auth\LoginController@logout')->name('logout');



Route::get('dashboard', 'DashboardController@index')->name('dashboard');
Route::get('about', 'DashboardController@about')->name('about');


