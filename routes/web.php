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
Route::get('/', 'Auth\LoginController@showLoginForm')->name('index');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

/*
*
*   Finish setting up user account Routes
*
*/
Route::get('/finish-setup/{hash}', 'Admin\UserController@initializeUser')->name('initialize');
Route::post('/finish-setup/{hash}', 'Admin\UserController@submitInitializeUser')->name('initialize');

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
Route::post('file-links/{id}', 'FileLinks\UserLinksController@update')->name('file-links.show');
Route::get('file-links/{id}', 'FileLinks\UserLinksController@show')->name('file-links.show');
Route::get('file-links', 'FileLinks\UserLinksController@index');

/*
*
*   User Change Password Routes
*
*/
Route::get('/account/change-password', 'AccountController@changePassword')->name('changePassword');
Route::post('/account/change-password', 'AccountController@submitPassword')->name('changePassword');

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
        *   User Account Settings
        *
        */
        Route::get('account', 'AccountController@index')->name('account');
        Route::post('account', 'AccountController@submit')->name('account');
        
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
        
        
        
        
        
        
        
        
        
  /////////////////////////////////////////////////////////////////////////////////////////////////      
        
        
        
        
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
    
    
////////////////////////////////////////////////////////////////////////////////////////////////////
    
    /*
    *
    *   Administration Routes
    *
    */
    Route::group(['middleware' => 'roles', 'roles' => ['installer', 'admin']], function()
    {
        
        
        Route::prefix('admin')->name('admin.')->group(function()
        {
            Route::get('user/confirm/{id}', 'Admin\UserController@confirm')->name('confirmDisable');
            Route::get('user/disable', 'Admin\UserController@disable')->name('disable');
            Route::get('user/change-password/{id}', 'Admin\UserController@changePassword')->name('changePassword');
            Route::post('user/change-password/{id}', 'Admin\UserController@submitPassword')->name('changePassword');
            Route::get('user/password', 'Admin\UserController@passwordList')->name('password');
            Route::resource('user', 'Admin\UserController');
            
            
            
            Route::get('/', 'Admin\AdminController@index')->name('index');
        });
        
    
        
        
    });
    
    
    
    
    
    
});






