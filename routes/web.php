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
Route::get('/download/{id}/{filename}', 'DownloadController@index')->name('downloadPage');
Route::get('/confirm', 'PagesController@confirmDialog')->name('confirm');

//  Tech/Registered User Routes
Route::group(['middleware' => 'roles', 'roles' => ['tech', 'report', 'admin', 'installer']], function()
{
    ///////////////////////////  Basic Routes  /////////////////////////////////////////////
    Route::get('/about', 'PagesController@about');
    Route::get('/dashboard', 'DashboardController@index');
    
    
    //////////////////////////  System Routes  ////////////////////////////////////////////
    Route::prefix('system')->name('system.')->group(function()
    {
        Route::get('sys-fields/{id}', 'SystemController@fields')->name('sysFields');
        Route::post('files/replace/{id}', 'SystemFilesController@replace')->name('files.replace');
        Route::get('files/replace/{id}', 'SystemFilesController@replaceForm')->name('files.replaceForm');
        Route::resource('files', 'SystemFilesController');
        Route::get('load-file/{sys}/{type}', 'SystemFilesController@loadFiles')->name('loadFiles');
        Route::get('{cat}/{sys}', 'SystemController@details')->name('details');
        Route::get('{cat}', 'SystemController@selectSystem') ->name('select');
        Route::get('/', 'SystemController@index')->name('index');
        
    });
    
    //////////////////////////  Customer Routes  ////////////////////////////////////////////
    Route::prefix('customer')->name('customer.')->group(function()
    {
        Route::get('fav/{action}/{id}', 'CustomerController@toggleFav')->name('toggleFav');
        
        
        Route::resource('files', 'CustomerFilesController');
        Route::resource('notes', 'CustomerNotesController');
        Route::get('download-contact/{id}', 'CustomerContactsController@downloadVCard')->name('vcard');
        Route::resource('contacts', 'CustomerContactsController');
        Route::get('systems/check-system/{id}/{sys}', 'CustomerSystemsController@checkSys')->name('checkSystem');
        Route::resource('systems', 'CustomerSystemsController');
        Route::get('id/{id}/{name}', 'CustomerDetails@details')->name('details');
        Route::resource('id', 'CustomerDetails');
        Route::post('search', 'CustomerController@search')->name('search');
        Route::get('/', 'CustomerController@index')->name('index');
    });
    
    
    
    
});
