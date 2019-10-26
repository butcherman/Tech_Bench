<?php

/*
*   Login/Logout Authorization Routes
*/
Auth::routes();

/*
*   Non user Routes
*/
Route::get('/', 'Auth\LoginController@showLoginForm')->name('index');
Route::get('logout', 'Auth\LoginController@logout')  ->name('logout');
Route::get('no-script', 'Controller@noScript')       ->name('noscript');


/*
*   Download File Routes
*/
Route::get('download/{id}/{filename}', 'DownloadController@index')->name('download');
Route::get('download-all', 'DownloadController@downloadAll')      ->name('downloadAll');
Route::put('download-all', 'DownloadController@flashDownload')    ->name('downloadAll');


/*
*   User Settings Routes
*/
Route::get('account', 'AccountController@index')                           ->name('account');
Route::post('account', 'AccountController@submit')                         ->name('account');
Route::put('account', 'AccountController@notifications')                   ->name('account');
Route::get('/account/change-password', 'AccountController@changePassword') ->name('changePassword');
Route::post('/account/change-password', 'AccountController@submitPassword')->name('changePassword');














/*
*
*   Finish setting up user account Routes
*
*/
//Route::get('/finish-setup/{hash}', 'Admin\UserController@initializeUser')->name('initialize');
//Route::post('/finish-setup/{hash}', 'Admin\UserController@submitInitializeUser')->name('initialize');

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


/*
*
*   Logged in user routes
*
*/
//Route::middleware(['password_expired'])->group(function() {

    /*
    *
    *   Routes for users with "tech" permissions
    *
    */
//    Route::group(['middleware' => 'roles', 'roles' => ['tech', 'report', 'admin', 'installer']], function() {

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
        Route::prefix('links')->name('links.')->group(function() {
            //  Resource controllers for base access
            Route::resource('data', 'FileLinks\FileLinksController');
            Route::get('find/{id}', 'FileLinks\FileLinksController@find')->name('user');
            Route::get('details/{id}/{name}', 'FileLinks\FileLinksController@details')->name('details');

            //  File Link Files
            Route::resource('files', 'FileLinks\LinkFilesController');

            //  File Link Instructions
            Route::get('instructions/{id}', 'FileLinks\InstructionsController@getIndex')->name('instructions');
            Route::post('instructions/{id}', 'FileLinks\InstructionsController@postIndex')->name('instructions');

            //  Link customer information
            Route::post('updateCustomer/{id}', 'FileLinks\FileLinksController@updateCustomer')->name('updateCustomer');

            //  Index landing page
            Route::get('/', 'FileLinks\FileLinksController@index')->name('index');
        });

        /*
        *
        *   System Routes
        *
        */
        Route::prefix('system')->name('system.')->group(function() {
            Route::post('system-files/replace', 'Systems\SystemFilesController@replace')->name('replaceFile');
            Route::resource('system-files', 'Systems\SystemFilesController');
            Route::get('{cat}/{sys}', 'Systems\SystemsController@details')->name('details');
            Route::get('{cat}', 'Systems\SystemsController@selectSys')->name('select');
            Route::get('/', 'Systems\SystemsController@index')->name('index');
        });

        /*
        *
        *   Customer Routes
        *
        */
        Route::prefix('customer')->name('customer.')->group(function() {
            Route::get('download-note/{id}', 'DownloadController@downloadCustNote')->name('download-note');
            Route::get('file-types', 'Customers\CustomerFilesController@getFileTypes')->name('getFileTypes');
            Route::get('sys-fields/{id}', 'Customers\CustomerSystemsController@getDataFields')->name('getDataFields');
            Route::get('toggle-fav/{action}/{custID}', 'Customers\CustomerController@toggleFav')->name('toggleFav');
            Route::get('check-id/{id}', 'Customers\CustomerController@checkID')->name('checkID');
            Route::get('id/{id}/{name}', 'Customers\CustomerDetailsController@details')->name('details');
            Route::get('search-id/{id}', 'Customers\CustomerController@searchID')->name('searchID');
            Route::get('search', 'Customers\CustomerController@search')->name('search');
            Route::get('/', 'Customers\CustomerController@index')->name('index');

            Route::resource('files', 'Customers\CustomerFilesController');
            Route::resource('notes', 'Customers\CustomerNotesController');
            Route::resource('contacts', 'Customers\CustomerContactsController');
            Route::resource('systems', 'Customers\CustomerSystemsController');
            Route::resource('id', 'Customers\CustomerDetailsController');
        });


        /*
        *
        *   Tech Tip Routes
        *
        */
//        Route::prefix('tip')->name('tip.')->group(function(){
//            Route::get('/', 'TechTips\TechTipsController@index')->name('index');
//            Route::resource('id', 'TechTips\TechTipsController');
//        });
        Route::get('tips/{id}/{subject}', 'TechTips\TechTipsController@details')->name('tips.details');
        Route::post('tips/search', 'TechTips\TechTipsController@search')->name('tips.search');
        Route::post('tips/process-image', 'TechTips\TechTipsController@processImage')->name('tips.processImage');
        Route::resource('tips', 'TechTips\TechTipsController');



//    });

    /*
    *
    *   Administration Routes
    *
    */
//    Route::group(['middleware' => 'can:allow_admin'], function() {
        Route::prefix('admin')->name('admin.')->group(function() {
            //  Admin User File Links routes
            Route::get('links/show/{id}', 'Admin\AdminController@showLinks')->name('userLinks');
            Route::get('count-links', 'Admin\AdminController@countLinks')->name('countLinks');
            Route::get('links', 'Admin\AdminController@userLinks')->name('links');

            //  Admin User routes
            Route::get('user/confirm/{id}', 'Admin\UserController@confirm')->name('confirmDisable');
            Route::get('user/disable', 'Admin\UserController@disable')->name('disable');
            Route::get('user/change-password/{id}', 'Admin\UserController@changePassword')->name('changePassword');
            Route::post('user/change-password/{id}', 'Admin\UserController@submitPassword')->name('changePassword');
            Route::get('user/password', 'Admin\UserController@passwordList')->name('password');
            Route::resource('user', 'Admin\UserController');

            //  Admin index route
            Route::get('/', 'Admin\AdminController@index')->name('index');
        });
//    });

    /*
    *
    *   Installer Routes
    *
    */
//    Route::group(['middleware' => 'can:isInstaller'], function() {
        Route::prefix('installer')->name('installer.')->group(function() {
            //  User Settings
            Route::get('user-security-settings', 'Installer\SettingsController@userSecurity')->name('userSecurity');
            Route::post('user-security-settings', 'Installer\SettingsController@submitUserSecurity')->name('userSecurity');

            //  System custionization settings
            Route::get('system-customization', 'Installer\SettingsController@customizeSystem')->name('customize');
            Route::post('system-customization', 'Installer\SettingsController@submitCustomizeSystem')->name('customize');
            Route::post('new-logo', 'Installer\SettingsController@submitLogo')->name('submitLogo');
            Route::post('email-settings', 'Installer\SettingsController@submitEmailSettings')->name('emailSettings');
            Route::put('email-settings', 'Installer\SettingsController@sendTestEmail')->name('emailSettings');
            Route::get('email-settings', 'Installer\SettingsController@emailSettings')->name('emailSettings');

            //  Categories and Systems settings
            Route::resource('categories', 'Installer\CategoriesController');
            Route::resource('systems', 'Installer\SystemsController');
        });
//    });
//});
