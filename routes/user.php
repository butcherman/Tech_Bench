<?php
/*
*   Basic User Authenticated Routes
*/
Route::middleware('auth')->group(function()
{
    //  Basic non-logic routes
    Route::view('/about', 'about')->name('about');
    Route::view('/change-password', 'auth.passwords.change')->name('change_password');

    //  User pages
    Route::get(   '/dashboard',          'DashboardController@index')           ->name('dashboard');
    Route::get(   '/notifications',      'DashboardController@getNotifications')->name('get_notifications');
    Route::get(   '/notifications/{id}', 'DashboardController@markNotification')->name('mark_notification');
    Route::delete('/notification/{id}',  'DashboardController@delNotification') ->name('del_notification');

    //  User Settings
    Route::get( '/settings',        'Auth\SettingsController@index')         ->name('settings');
    Route::put( '/settings',        'Auth\SettingsController@updateSettings')->name('update_settings');
    Route::post('/settings',        'Auth\SettingsController@updateAccount') ->name('update_account');
    Route::post('/change-password', 'Auth\SettingsController@changePassword')->name('submit_password');
});
