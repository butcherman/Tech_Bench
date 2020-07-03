<?php
/*
*   Tech Tip Routes
*/
Route::middleware('auth')->prefix('tips')->name('tips.')->group(function()
{

    Route::get('details/{id}/{name}', 'TechTips\TechTipsController@show')  ->name('details');
    Route::get('create',              'TechTips\TechTipsController@create')->name('create');
    Route::get('search',              'TechTips\TechTipsController@search')->name('search');
    Route::get('/',                   'TechTips\TechTipsController@index') ->name('index');
});
