<?php

/*
*
*   Login/Logout Authorization Routes
*
*/
Auth::routes();

/*
*
*   Non user Routes
*
*/
Route::get('/', 'Auth\LoginController@showLoginForm');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

/*
*
*   Download File Routes
*
*/
Route::get('/download/{id}/{filename}', 'DownloadController@index')->name('download');
Route::get('download-all', 'DownloadController@downloadAll')->name('downloadAll');
Route::put('download-all', 'DownloadController@flashDownload')->name('downloadAll');

/*
*
*   User File Link Routes
*
*/
//Route::resource('file-links', 'FileLinks\UserLinksController');
Route::post('file-links/{id}', 'FileLinks\UserLinksController@update')->name('file-links.show');
Route::get('file-links/{id}', 'FileLinks\UserLinksController@show')->name('file-links.show');
Route::get('file-links', 'FileLinks\UserLinksController@index');

/*
*
*   Logged in user routes
*
*/
Route::middleware(['password_expired'])->group(function()
{
    /*
    *
    *   Routes for users with "tech" permissions
    *
    */
    Route::group(['middleware' => 'roles', 'roles' => ['tech', 'report', 'admin', 'installer']], function()
    {
        /*
        *
        *   Dashboard and About page routes
        *
        */
        Route::get('about', 'DashboardController@about')->name('about');         
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('get-notifications', 'DashboardController@getNotifications')->name('get-notifications');
        Route::get('del-notification/{id}', 'DashboardController@delNotification')->name('del-notification');
        
        /*
        *
        *   File Link Routes
        *
        */
        Route::prefix('links')->name('links.')->group(function()
        {
            //  Resource controllers for base access
            Route::resource('data', 'FileLinks\FileLinksController');
            Route::get('find/{id}', 'FileLinks\FileLinksController@find')->name('user');
            Route::get('details/{id}/{name}', 'FileLinks\FileLinksController@details')->name('details');
            
            //  File Link Files
            Route::get('files/{id}/{dir}', 'FileLinks\LinkFilesController@getIndex')->name('files');
            Route::post('files/{id}/{dir}', 'FileLinks\LinkFilesController@postIndex')->name('files');
            Route::put('files/{id}/{dir}', 'FileLinks\LinkFilesController@moveFile')->name('files');
            Route::delete('files/{id}', 'FileLinks\LinkFilesController@delFile')->name('delFile');
            
            //  File Link Instructions
            Route::get('instructions/{id}', 'FileLinks\InstructionsController@getIndex')->name('instructions');
            Route::post('instructions/{id}', 'FileLinks\InstructionsController@postIndex')->name('instructions');
            
            //  Link customer information
            Route::post('updateCustomer/{id}', 'FileLinks\FileLinksController@updateCustomer')->name('updateCustomer');
            
            //  Index landing page
            Route::get('/', 'FileLinks\FileLinksController@index')->name('index'); 
        });
        
        
        
        
        
        
        
        
        
  ///////////////////////////////////////////////////////////////////////////////////      
        
        
        
        
        /*
        *
        *   Customer Routes
        *
        */        
        Route::prefix('customer')->name('customer.')->group(function()
        {

            Route::get('search-id/{id}', 'CustomerController@searchID')->name('searchID');
            Route::get('file-types', 'CustomerFilesController@getFileTypes')->name('getFileTypes');

        });
        
        
        
        
        
        
    });
    
    
    
    /////////////////////////////////////////////////////////////////////////////
    
    /*
    *
    *   Administration Routes
    *
    */
    Route::group(['middleware' => 'roles', 'roles' => ['installer', 'admin']], function()
    {
        
        
        
        Route::get('admin', function(){
            echo '<pre>';
            print_r(Auth::user());
        })->name('admin.index');
        
        
        
    });
    
    
    
    
    
    
});







