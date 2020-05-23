<?php

use Illuminate\Support\Facades\Auth;
/*
*   Built in Authorization Routes
*/
Auth::routes(['register' => false]);

/*
*   Basic Non-User Routes
*/
Route::middleware('guest')->group(function()
{
    Route::view('/', 'auth.login')->name('index');
});










Route::get('/logout', function()
{
    Auth::logout();


});

Route::get('/change-password', function()
{
    return response('change password');
})->name('change_password');

Route::get('/dashboard', function()
{
    return response('worked');
})->middleware('auth')->name('dashboard');
