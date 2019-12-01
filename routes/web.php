<?php

/*
*   Login/Logout Authorization Routes
*/
Auth::routes();

/*
*   Non user Routes
*/
Route::get('/',         'Auth\LoginController@showLoginForm')->name('index');
Route::get('logout',    'Auth\LoginController@logout')       ->name('logout');
Route::get('no-script', 'Controller@noScript')               ->name('noscript');

/*
*   Download File Routes
*/
Route::get('download/{id}/{filename}',    'DownloadController@index')          ->name('download');
Route::get('download-archive/{filename}', 'DownloadController@downloadArchive')->name('downloadArchive');
Route::put('download-archive',            'DownloadController@archiveFiles')   ->name('archiveFiles');

/*
*   User Settings Routes
*/
Route::get('account',                   'AccountController@index')         ->name('account');
Route::post('account',                  'AccountController@submit')        ->name('account');
Route::put('account',                   'AccountController@notifications') ->name('account');
Route::get('/account/change-password',  'AccountController@changePassword')->name('changePassword');
Route::post('/account/change-password', 'AccountController@submitPassword')->name('changePassword');

/*
*   Basic Logged In Routes
*/
Route::get('about',                  'DashboardController@about')           ->name('about');
Route::get('dashboard',              'DashboardController@index')           ->name('dashboard');
Route::get('notifications',          'DashboardController@getNotifications')->name('getNotifications');
Route::get('mark-notification/{id}', 'DashboardController@markNotification')->name('markNotification');
Route::delete('notifications/{id}',  'DashboardController@delNotification') ->name('delNotification');

/*
*   File Link Routes
*/
Route::prefix('links')->name('links.')->group(function () {
    //  Resource controllers for base access
    Route::resource('data',           'FileLinks\FileLinksController');
    Route::get('new',                 'FileLinks\FileLinksController@create')     ->name('new');
    Route::get('find/{id}',           'FileLinks\FileLinksController@find')       ->name('user');
    Route::get('details/{id}/{name}', 'FileLinks\FileLinksController@details')    ->name('details');
    Route::get('disable/{id}',        'FileLinks\FileLinksController@disableLink')->name('disable');
    //  File Link Files
    Route::resource('files',          'FileLinks\LinkFilesController');
    //  Index landing page
    Route::get('/',                   'FileLinks\FileLinksController@index')      ->name('index');
});

/*
*   Guest File Link Routes
*/
Route::get('file-links/{id}/get-files', 'FileLinks\GuestLinksController@getFiles')->name('file-links.getFiles');
Route::put('file-links/{id}',           'FileLinks\GuestLinksController@notify')  ->name('file-links.show');
Route::post('file-links/{id}',          'FileLinks\GuestLinksController@update')  ->name('file-links.show');
Route::get('file-links/{id}',           'FileLinks\GuestLinksController@show')    ->name('file-links.show');
Route::get('file-links',                'FileLinks\GuestLinksController@index')   ->name('file-links.index');

/*
*   Customer Routes
*/
Route::prefix('customer')->name('customer.')->group(function()
{
    //  Customer Files
    Route::resource('files',                   'Customers\CustomerFilesController');
    //  Custome Notes
    Route::get('download-note/{id}',           'DownloadController@downloadCustNote')        ->name('download-note');
    Route::resource('notes',                   'Customers\CustomerNotesController');
    //  Customer Contacts
    Route::resource('contacts',                'Customers\CustomerContactsController');
    //  Customer Systems
    Route::resource('systems',                 'Customers\CustomerSystemsController');
    //  Customer Details
    Route::get('id/{id}/{name}',               'Customers\CustomerDetailsController@details')->name('details');
    Route::resource('id',                      'Customers\CustomerDetailsController');
    //  check Id and bookmark customer
    Route::get('toggle-fav/{action}/{custID}', 'Customers\CustomerController@toggleFav')     ->name('toggle-fav');
    Route::get('check-id/{id}',                'Customers\CustomerController@checkID')       ->name('check-id');
    //  Index landing/search page
    Route::get('search',                       'Customers\CustomerController@search')        ->name('search');
    Route::get('/',                            'Customers\CustomerController@index')         ->name('index');
});

/*
*   Tech Tip Routes
*/
Route::resource('tips',                       'TechTips\TechTipsController');
Route::post('submit-edit/{id}',               'TechTips\TechTipsController@update')      ->name('tips.submit-edit');
Route::prefix('tip')->name('tip.')->group(function()
{
    Route::resource('comments',               'TechTips\TechTipCommentsController');
    Route::get('search',                      'TechTips\TechTipsController@search')      ->name('search');
    Route::get('details/{id}/{name}',         'TechTips\TechTipsController@details')     ->name('details');
    Route::post('process-image',              'TechTips\TechTipsController@processImage')->name('processImage');
    Route::get('toggle-fav/{action}/{tipID}', 'TechTips\TechTipsController@toggleFav')   ->name('toggle-fav');
    Route::get('download-tip/{id}',           'DownloadController@downloadTechTip')      ->name('downloadTip');
});





















//        Route::prefix('tip')->name('tip.')->group(function(){
//            Route::get('/', 'TechTips\TechTipsController@index')->name('index');
//            Route::resource('id', 'TechTips\TechTipsController');
//        });

// Route::prefix('tips')->name('tips.')->group(function ()
// {
    // Route::get('tips/{id}/{subject}', 'TechTips\TechTipsController@details')->name('tips.details');
//     Route::get('/', 'TechTips\TechTipsController@index');
// });


/*
*
*   Finish setting up user account Routes
*
*/
//Route::get('/finish-setup/{hash}', 'Admin\UserController@initializeUser')->name('initialize');
//Route::post('/finish-setup/{hash}', 'Admin\UserController@submitInitializeUser')->name('initialize');



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

        Route::get('get-notifications', 'DashboardController@getNotifications')->name('get-notifications');
        Route::get('del-notification/{id}', 'DashboardController@delNotification')->name('del-notification');





        /*
        *
        *   System Routes
        *
        */
        // Route::prefix('system')->name('system.')->group(function() {
        //     Route::post('system-files/replace', 'Systems\SystemFilesController@replace')->name('replaceFile');
        //     Route::resource('system-files', 'Systems\SystemFilesController');
        //     Route::get('{cat}/{sys}', 'Systems\SystemsController@details')->name('details');
        //     Route::get('{cat}', 'Systems\SystemsController@selectSys')->name('select');
        //     Route::get('/', 'Systems\SystemsController@index')->name('index');
        // });









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
