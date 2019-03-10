<?php

//  Login/Logout and Authorization routes
Auth::routes();

/*
*
*   Non user Routes
*
*/
Route::get('/', 'Auth\LoginController@showLoginForm');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');




///////////////////////////  User File Link Routes to be adjusted  //////////////////////////////////////
Route::prefix('file-links')->name('userLink.')->group(function()
{
    Route::get('download-all/{link}', 'UserLinksController@downloadAllFiles')->name('downloadAll');
    Route::post('details/{link}', 'UserLinksController@uploadFiles')->name('upload');
    Route::get('details/{link}', 'UserLinksController@details')->name('details');
    Route::get('/', 'UserLinksController@index')->name('index');
});







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
        Route::get('about', 'DashboardController@about')->name('about');         
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        
        //  File Link Routes
        Route::prefix('links')->name('links.')->group(function()
        {
            
            
            
            Route::resource('data', 'FileLinksController');
            
            
            
            
            Route::get('/details/{id}/{name}', 'FileLinksController@details')->name('details');
            Route::get('/', 'FileLinksController@index')->name('index');                                            
        });
        
        
        
        
        /*
        *
        *   Customer Routes
        *
        */        
        Route::prefix('customer')->name('customer.')->group(function()
        {

            Route::get('search-id/{id}', 'CustomerController@searchID')->name('searchID');

        });
        
        
        
        
        
        
    });
    
    
    
    
    
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







