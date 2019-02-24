<?php

//  Login/Logout and Authorization routes
Auth::routes();

///////////////////////////  Basic Non user Routes  /////////////////////////////////////////
Route::get('/', 'Auth\LoginController@showLoginForm');
Route::get('/download/{id}/{filename}', 'DownloadController@index')->name('downloadPage');
Route::get('/confirm', 'PagesController@confirmDialog')->name('confirm');
Route::get('/finish-setup/{hash}', 'UserController@initializeUser')->name('initialize');
Route::post('/finish-setup/{hash}', 'UserController@submitInitializeUser')->name('submitInitialize');

///////////////////////////  User File Link Routes  /////////////////////////////////////////
Route::prefix('file-links')->name('userLink.')->group(function()
{
    Route::get('download-all/{link}', 'UserLinksController@downloadAllFiles')->name('downloadAll');
    Route::post('details/{link}', 'UserLinksController@uploadFiles')->name('upload');
    Route::get('details/{link}', 'UserLinksController@details')->name('details');
    Route::get('/', 'UserLinksController@index')->name('index');
});

//  Change Password Route applies to Registered Users with any role assigned
Route::get('account/change-password', 'AccountController@changePassword')->name('changePassword');
Route::post('account/change-password', 'AccountController@submitPassword')->name('submitPassword');

Route::middleware(['password_expired'])->group(function () 
{ 
    //  Tech/Registered User Routes
    Route::group(['middleware' => 'roles', 'roles' => ['tech', 'report', 'admin', 'installer']], function()
    {
        ///////////////////////////  Basic User Routes  /////////////////////////////////////////
        Route::get('about', 'PagesController@about')->name('about');
        Route::get('account', 'AccountController@index')->name('account');
        Route::post('account/{id}', 'AccountController@submit')->name('submitAccount');
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('mark-notification/{id}', 'DashboardController@markNotification')->name('mark-notification');

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
            Route::get('download-note/{id}', 'CustomerController@generatePDF')->name('downloadNote');
            Route::get('fav/{action}/{id}', 'CustomerController@toggleFav')->name('toggleFav');
            Route::resource('files', 'CustomerFilesController');
            Route::resource('notes', 'CustomerNotesController');
            Route::get('download-contact/{id}', 'CustomerContactsController@downloadVCard')->name('vcard');
            Route::resource('contacts', 'CustomerContactsController');
            Route::get('systems/check-system/{id}/{sys}', 'CustomerSystemsController@checkSys')->name('checkSystem');
            Route::resource('systems', 'CustomerSystemsController');
            Route::resource('id', 'CustomerDetails');
            Route::get('id/{id}/{name}', 'CustomerDetails@details')->name('details');
            Route::post('search', 'CustomerController@search')->name('search');
            Route::get('check-id/{id}', 'CustomerController@checkId')->name('checkId');
            Route::get('/', 'CustomerController@index')->name('index');
        });

        //////////////////////////  Tech Tips Routes  //////////////////////////////////////////
        Route::prefix('tip')->name('tip.')->group(function()
        {
            Route::get('download-note/{id}', 'TechTipsController@generatePDF')->name('downloadTip');
            Route::post('upload-image', 'TechTipsController@processImage')->name('processImage');
            Route::get('fav/{action}/{id}', 'TechTipsController@toggleFav')->name('toggleFav');
            Route::resource('comments', 'TechTipsCommentsController');
            Route::get('details/{id}/{name}', 'TechTipsController@details')->name('details');
            Route::resource('id', 'TechTipsController');
            Route::post('search', 'TechTipsController@search')->name('search');
            Route::get('/', 'TechTipsController@index')->name('index');
        });

        //////////////////////////  File Links Routes  //////////////////////////////////////////
        Route::prefix('links')->name('links.')->group(function()
        {
            Route::get('download-all/{id}', 'FileLinksController@downloadAllFiles')->name('downloadAll');
            Route::post('addFile/{id}', 'FileLinksController@submitAddFile')->name('submitAdd');
            Route::get('addFile/{id}', 'FileLinksController@addFileForm')->name('addFile');
            Route::get('note/{id}', 'FileLinksController@getNote')->name('note');
            Route::delete('deleteFile/{id}', 'FileLinksController@deleteLinkFile')->name('deleteFile');
            Route::get('getFiles/{type}/{id}', 'FileLinksController@getFiles')->name('getFiles');
            Route::resource('details', 'FileLinksController');
            Route::get('details/{id}/{name}', 'FileLinksController@details')->name('info');
            Route::get('/', 'FileLinksController@index')->name('index');
        });
    });

    //  Administration Routes
    Route::group(['middleware' => 'roles', 'roles' => ['installer', 'admin']], function()
    {
        Route::prefix('admin')->name('admin.')->group(function()
        {
            Route::get('links/show/{id}', 'AdminController@showLinks')->name('showLinks');
            Route::get('links', 'AdminController@links')->name('links');
            Route::post('resetPass/{id}', 'UserController@resetPassword')->name('resetPass');
            Route::resource('users', 'UserController');
            Route::get('customers', 'AdminController@customers')->name('customers');
            Route::get('/', 'AdminController@index')->name('index');
        });
    });

    //  Installer Routes
    Route::group(['middleware' => 'roles', 'roles' => ['installer']], function()
    {
        Route::prefix('installer')->name('installer.')->group(function()
        {
            ///////////////////////  Systems and Categories Routes  //////////////////////////////
            Route::resource('system-categories', 'SystemCategoriesController');
            Route::get('systems/new/{cat}', 'SystemTypesController@newSys')->name('newSys');
            Route::post('systems/new/{cat}', 'SystemTypesController@submitSys')->name('submitSys');
            Route::resource('systems', 'SystemTypesController');
            Route::get('/', 'InstallerController@index')->name('index');

            //////////////////////////  System Customization Routes  ////////////////////////////
            Route::post('system-customization', 'InstallerController@submitCustom')->name('submitCustomize');
            Route::get('system-customization', 'InstallerController@customizeSystem')->name('customize');
            Route::post('email-settings', 'InstallerController@submitEmailSettings')->name('submitEmail');
            Route::get('email-settings', 'InstallerController@emailSettings')->name('email');
            Route::post('test-email', 'InstallerController@sendTestEmail')->name('testEmail');
            Route::get('user-settings', 'InstallerController@userSettings')->name('userSettings');
            Route::post('user-settings', 'InstallerController@submitUserSettings')->name('submitUserSettings');
            Route::get('view-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');
            Route::post('submit-logo', 'InstallerController@submitLogo')->name('submitLogo');
            
            /////////////////////////////  System Backups Routes  //////////////////////////////
            Route::get('backup', 'BackupController@index')->name('backup');
            Route::get('load-backups', 'BackupController@loadBackups')->name('loadBackups');
            Route::get('manual-backup', 'BackupController@backupNow')->name('backupNow');
            Route::get('download-backup/{name}', 'BackupController@downloadBackup')->name('downloadBackup');
            Route::delete('delete-backup/{name}', 'BackupController@deleteBackup')->name('destroyBackup');
        });
    });
});
