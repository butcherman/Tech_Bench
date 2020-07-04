<?php
/*
*   Tech Tip Routes
*/
Route::middleware('auth')->prefix('tips')->name('tips.')->group(function()
{
    //  Rotues for creating Tech Tips
    Route::middleware('check_role:Create Tech Tip')->group(function()
    {

        Route::post('upload-image',   'TechTips\TechTipsController@uploadImage')->name('upload_image');
        Route::post('store',          'TechTips\TechTipsController@store')      ->name('store');
        Route::get('create',          'TechTips\TechTipsController@create')     ->name('create');
    });


    Route::get('details/{id}/{name}', 'TechTips\TechTipsController@show')  ->name('details');
    Route::get('search',              'TechTips\TechTipsController@search')->name('search');
    Route::get('/',                   'TechTips\TechTipsController@index') ->name('index');
});
