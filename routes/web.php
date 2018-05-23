<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//  Login/Logout and Authorization routes
Auth::routes();

//  Non user Routes
Route::get('/', 'Auth\LoginController@showLoginForm');
Route::get('/download/{id}/{filename}', 'DownloadController@index');
Route::get('/confirm', 'PagesController@confirmDialog');

//  Tech/Registered User Routes
Route::group(['middleware' => 'roles', 'roles' => ['tech', 'report', 'admin', 'installer']], function()
{
    ///////////////////////////  Basic Routes  /////////////////////////////////////////////
    Route::get('/about', 'PagesController@about');
    Route::get('/dashboard', 'DashboardController@index');
    
    
    
    
    
    
    
    
    
});
