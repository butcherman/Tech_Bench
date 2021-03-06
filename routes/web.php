<?php

/*
*   Login/Logout Authorization Routes
*/
Auth::routes();

/*
*   Non user Routes
*/
Route::get('/',                    'Auth\LoginController@showLoginForm')                ->name('index');
Route::get('logout',               'Auth\LoginController@logout')                       ->name('logout');
Route::get('no-script',            'Controller@noScript')                               ->name('noscript');
Route::get('finish-setup/{hash}',  'Auth\InitializeUserController@initializeUser')      ->name('initialize');
Route::post('finish-setup/{hash}', 'Auth\InitializeUserController@submitInitializeUser')->name('initialize');

/*
*   Download File Routes
*/
Route::post('download/archive',           'DownloadController@archive')        ->name('dlArchive');
Route::get('download/{id}/{filename}',    'DownloadController@index')          ->name('download');
Route::get('download-archive/{filename}', 'DownloadController@downloadArchive')->name('downloadArchive');
// Route::put('download-archive',            'DownloadController@archiveFiles')   ->name('archiveFiles');

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
    Route::get('new',                 'FileLinks\FileLinksController@create')            ->name('new');
    Route::get('find/{id}',           'FileLinks\FileLinksController@find')              ->name('user');
    Route::get('instructions/{id}',   'FileLinks\FileLinksController@getInstructions')   ->name('getInstructions');
    Route::post('instructions/{id}',  'FileLinks\FileLinksController@submitInstructions')->name('submitInstructions');
    Route::get('details/{id}/{name}', 'FileLinks\FileLinksController@details')           ->name('details');
    Route::get('disable/{id}',        'FileLinks\FileLinksController@disableLink')       ->name('disable');
    //  File Link Files
    Route::resource('files',          'FileLinks\LinkFilesController');
    //  Index landing page
    Route::get('/',                   'FileLinks\FileLinksController@index')             ->name('index');
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
Route::prefix('customer')->name('customer.')->group(function() {
    //  Customer Files
    Route::resource('files',                    'Customers\CustomerFilesController');
    //  Custome Notes
    Route::get('download-note/{id}',            'DownloadController@downloadCustNote')             ->name('download-note');
    Route::resource('notes',                    'Customers\CustomerNotesController');
    //  Customer Contacts
    Route::resource('contacts',                 'Customers\CustomerContactsController');
    //  Customer Equipemtn
    Route::delete('systems/delete/{id}/{cust}', 'Customers\CustomerSystemsController@deleteEquip')  ->name('delEquip');
    Route::resource('systems',                  'Customers\CustomerSystemsController');
    //  Customer Details
    Route::get('link-parent/{id}',              'Customers\CustomerDetailsController@removeParent')->name('removeParent');
    Route::post('link-parent',                  'Customers\CustomerDetailsController@linkParent')  ->name('linkParent');
    Route::get('id/{id}/{name}',                'Customers\CustomerDetailsController@details')     ->name('details');
    Route::resource('id',                       'Customers\CustomerDetailsController');
    //  check Id and bookmark customer
    Route::get('toggle-fav/{action}/{custID}',  'Customers\CustomerController@toggleFav')          ->name('toggle-fav');
    Route::get('check-id/{id}',                 'Customers\CustomerController@checkID')            ->name('check-id');
    //  Index landing/search page
    Route::get('search',                        'Customers\CustomerController@search')             ->name('search');
    Route::get('/',                             'Customers\CustomerController@index')              ->name('index');
});

/*
*   Tech Tip Routes
*/
Route::resource('tips',                       'TechTips\TechTipsController');
Route::post('submit-edit/{id}',               'TechTips\TechTipsController@update')      ->name('tips.submit-edit');
Route::prefix('tip')->name('tip.')->group(function() {
    Route::resource('comments',               'TechTips\TechTipCommentsController');
    Route::get('search',                      'TechTips\TechTipsController@search')      ->name('search');
    Route::get('details/{id}/{name}',         'TechTips\TechTipsController@details')     ->name('details');
    Route::post('process-image',              'TechTips\TechTipsController@processImage')->name('processImage');
    Route::get('toggle-fav/{action}/{tipID}', 'TechTips\TechTipsController@toggleFav')   ->name('toggle-fav');
    Route::get('download-tip/{id}',           'DownloadController@downloadTechTip')      ->name('downloadTip');
});

/*
*   Administration Routes
*/
Route::prefix('admin')->name('admin.')->group(function () {
    //  Routes for Tech Bench Add-ons
    Route::prefix('modules')->name('module.')->group(function() {
        Route::delete('delete-staged/{name}', 'Installer\ModuleController@delStaged')            ->name('deleteStaged');
        Route::post('upload',                 'Installer\ModuleController@upload')               ->name('upload');
        Route::get('activate/{name}',         'Installer\ModuleController@activate')             ->name('activate');
        Route::get('disable/{name}',          'Installer\ModuleController@disable')              ->name('disable');
        Route::get('enable/{name}',           'Installer\ModuleController@enable')               ->name('enable');
        Route::get('get-active',              'Installer\ModuleController@getEnabled')           ->name('getEnabled');
        Route::get('get-staged',              'Installer\ModuleController@getStaged')            ->name('getStaged');
        Route::get('/',                       'Installer\ModuleController@index')                ->name('index');
    });
    //  Administrative routes for equipment and equipment categories
    Route::resource('categories',             'Installer\CategoriesController');
    Route::resource('systems',                'Installer\SystemsController');
    //  Administrative routes for users
    Route::resource('user',                   'Admin\UserController');
    Route::get('user/enable/{id}',            'Admin\UserController@reactivateUser')             ->name('user.reactivate');
    Route::post('user/change-password',       'Admin\UserController@submitPassword')             ->name('user.changePassword');
    Route::get('check-user/{name}/{type}',    'Admin\UserController@checkUser')                  ->name('checkUser');
    Route::get('links/show/{id}',             'Admin\AdminController@showLinks')                 ->name('user.showLinks');
    Route::get('links',                       'Admin\AdminController@userLinks')                 ->name('user.links');
    Route::post('password-policy',            'Admin\AdminController@submitPolicy')              ->name('passwordPolicy');
    Route::get('password-policy',             'Admin\AdminController@passwordPolicy')            ->name('passwordPolicy');
    Route::post('role-policy',                'Admin\AdminController@submitRoleSettings')        ->name('roleSettings');
    Route::get('role-policy',                 'Admin\AdminController@roleSettings')              ->name('roleSettings');
    //  Administrative routes for customers
    Route::get('customer-id',                 'Admin\CustomerAdminController@modifyIdIndex')     ->name('customerID');
    Route::post('customer-id',                'Admin\CustomerAdminController@updateId')          ->name('submitCustomerID');
    Route::delete('customer-file-types/{id}', 'Admin\CustomerAdminController@delFileType')       ->name('delCustFileType');
    Route::post('customer-file-types',        'Admin\CustomerAdminController@submitNewFileType') ->name('submitNewFileType');
    Route::put('customer-file-types/set',     'Admin\CustomerAdminController@setFileTypes')      ->name('setCustFileTypes');
    Route::get('customer-file-types/get',     'Admin\CustomerAdminController@getFileTypes')      ->name('getCustFileTypes');
    Route::get('customer-file-types',         'Admin\CustomerAdminController@fileTypes')         ->name('custFileTypes');
    Route::get('disabled',                    'Admin\CustomerAdminController@showDisabled')      ->name('disabledCustomers');
    Route::get('enable/{id}',                 'Admin\CustomerAdminController@enableCustomer')    ->name('enableCustomer');
    //  Tech Bench Settings Routes
    Route::post('logo',                       'Installer\SettingsController@submitLogo')         ->name('submitLogo');
    Route::get('logo',                        'Installer\SettingsController@logoSettings')       ->name('logoSettings');
    Route::post('configuratin',               'Installer\SettingsController@submitConfiguration')->name('submitConfig');
    Route::get('configuration',               'Installer\SettingsController@configuration')      ->name('config');
    Route::post('email-settings',             'Installer\SettingsController@submitEmailSettings')->name('emailSettings');
    Route::put('email-settings',              'Installer\SettingsController@sendTestEmail')      ->name('emailSettings');
    Route::get('email-settings',              'Installer\SettingsController@emailSettings')      ->name('emailSettings');
    //  Tech Bench Backup Routes
    Route::get('backups/download/{name}',     'Installer\SettingsController@downloadBackup')     ->name('downloadBackup');
    Route::get('backups/delete/{name}',       'Installer\SettingsController@delBackup')          ->name('delBackup');
    Route::get('backups/run',                 'Installer\SettingsController@runBackup')          ->name('runBackup');
    Route::get('backups/get',                 'Installer\SettingsController@getBackups')         ->name('getBackups');
    Route::get('backups',                     'Installer\SettingsController@backupsIndex')       ->name('backups');
    //  Tech Bench Updates Routs
    Route::get('updates',                     'Installer\UpdateController@index')                ->name('updates');
    //  Admin index route
    Route::get('/',                           'Admin\AdminController@index')                     ->name('index');
});
