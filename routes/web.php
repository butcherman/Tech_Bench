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







