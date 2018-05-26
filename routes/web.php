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
        Route::post('files/replace/{id}', 'SystemFilesController@replace')->name('files.replace');
        Route::get('files/replace/{id}', 'SystemFilesController@replaceForm')->name('files.replaceForm');
        Route::resource('files', 'SystemFilesController');
        Route::get('load-file/{sys}/{type}', 'SystemFilesController@loadFiles')->name('loadFiles');
        Route::get('{cat}/{sys}', 'SystemController@details')->name('details');
        Route::get('{cat}', 'SystemController@selectSystem') ->name('select');
        Route::get('/', 'SystemController@index');
        
    });
    
    
    
    
    
    
    
});
