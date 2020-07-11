<?php
/*
*   Tech Tip Routes
*/
Route::middleware('auth')->prefix('tips')->name('tips.')->group(function()
{
    //  Routes for creating Tech Tips
    Route::middleware('check_role:Create Tech Tip')->group(function()
    {
        Route::post('upload-image',   'TechTips\TechTipsController@uploadImage')->name('upload_image');
        Route::post('store',          'TechTips\TechTipsController@store')      ->name('store');
        Route::get('create',          'TechTips\TechTipsController@create')     ->name('create');
    });

    //  Routes for editing Tech Tips
    Route::middleware('check_role:Edit Tech Tip')->group(function()
    {
        Route::post('edit/{id}',      'TechTips\TechTipsController@update')     ->name('update');
        Route::get('edit/{id}',       'TechTips\TechTipsController@edit')       ->name('edit');
    });

    //  Route for deleting Tech Tip
    Route::middleware('check_role:Delete Tech Tip')->group(function()
    {
        Route::delete('edit/{id}',    'TechTips\TechTipsController@destroy')    ->name('destroy');
    });

    //  General Tech Tip Routes
    Route::resource('comments',       'TechTips\TipCommentsController');
    Route::get('download/{id}',       'TechTips\TechTipsController@download')   ->name('download');
    Route::get('toggle-fav/{id}',     'TechTips\TechTipsController@toggleFav')  ->name('toggle_fav');
    Route::get('details/{id}/{name}', 'TechTips\TechTipsController@show')       ->name('details');
    Route::get('search',              'TechTips\TechTipsController@search')     ->name('search');
    Route::get('/',                   'TechTips\TechTipsController@index')      ->name('index');
});
