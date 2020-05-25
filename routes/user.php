<?php
/*
*   Basic User Authenticated Routes
*/
Route::middleware('auth')->group(function()
{
    //  Basic non-logic routes
    Route::view('/about', 'about')->name('about');

    //  User pages
    Route::get('/dashboard',            'DashboardController@index')           ->name('dashboard');
    Route::get('/notifications',        'DashboardController@getNotifications')->name('get_notifications');
    Route::get('/notifications/{id}',   'DashboardController@markNotification')->name('mark_notification');
    Route::delete('/notification/{id}', 'DashboardController@delNotification') ->name('del_notification');






    Route::get('/change-password', function()
    {
        return response('change password');
    })->name('change_password');

});




Route::get('/customer/stuff/{id}/{name}', function()
{
    return 'customer';
})->name('customer.details');
Route::get('tips/stuff/{id}/{name}', function()
{
    return 'Tech Tip';
})->name('tip.details');
