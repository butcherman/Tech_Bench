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
    Route::post('details/{link}', 'UserLinksController@uploadFiles')->name('upload');
    Route::get('details/{link}', 'UserLinksController@details')->name('details');
    Route::get('/', 'UserLinksController@index')->name('index');
});

//  Tech/Registered User Routes
Route::group(['middleware' => 'roles', 'roles' => ['tech', 'report', 'admin', 'installer']], function()
{
    ///////////////////////////  Basic User Routes  /////////////////////////////////////////
    Route::get('about', 'PagesController@about')->name('about');
    Route::get('account', 'AccountController@index')->name('account');
    Route::get('account/change-password', 'AccountController@changePassword')->name('changePassword');
    Route::post('account/change-password', 'AccountController@submitPassword')->name('submitPassword');
    Route::post('account/{id}', 'AccountController@submit')->name('submitAccount');
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    
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
    
    //////////////////////////  Tech Tips Routes  //////////////////////////////////////////
    Route::prefix('tip')->name('tip.')->group(function()
    {
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
        Route::get('/', 'AdminController@index')->name('index');
    });
});

//  Installer Routes
Route::group(['middleware' => 'roles', 'roles' => ['installer']], function()
{
    Route::prefix('installer')->name('installer.')->group(function()
    {
        ///////////////////////  Systems and Categories Routes  //////////////////////////////
        Route::post('edit-system/{name}', 'InstallerController@submitEditSystem')->name('submitEditSystem');
        Route::get('edit-system/{name}', 'InstallerController@editSystem')->name('editSystem');
        Route::post('new-category', 'InstallerController@submitCat')->name('submitCat');
        Route::get('new-category', 'InstallerController@newCat')->name('newCat');
        Route::post('{cat}/new-system', 'InstallerController@submitSys')->name('submitSys');
        Route::get('{cat}/new-system', 'InstallerController@newSystem')->name('newSys');
        Route::get('/', 'InstallerController@index')->name('index');
        
        //////////////////////////  System Customization Routes  ////////////////////////////
        Route::post('system-customization', 'InstallerController@submitCustom')->name('submitCustomize');
        Route::get('system-customization', 'InstallerController@customizeSystem')->name('customize');
    });
});
