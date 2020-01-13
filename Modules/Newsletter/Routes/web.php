<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::prefix('newsletter')->group(function() {
    Route::get('/', 'NewsletterController@index');
});*/

Route::group(['prefix'=>'admin/newsletter/list','middleware' => ['auth']], function () {
    Route::get('/', 'NewsletterController@index')->name('subs-lists');
    Route::get('/create', 'NewsletterController@create')->name('create_subs_list');
    Route::post('/store', 'NewsletterController@store')->name('store_subs_list');
    Route::get('/list', 'NewsletterController@list')->name('subs_list_list');
    Route::get('/edit/{id}', 'NewsletterController@edit')->name('subs_list_edit');
    Route::post('/update/{id}', 'NewsletterController@update')->name('subs_list_update');
    Route::post('/delete/{id}', 'NewsletterController@destroy')->name('subs_list_delete');
    Route::get('/view/{id}', 'NewsletterController@view')->name('subs_list_view');
});

Route::group(['prefix'=>'admin/newsletter/subscribers','middleware' => ['auth']], function () {
    Route::get('/', 'SubscribersController@index')->name('subs');
    Route::get('/create', 'SubscribersController@create')->name('create_subs');
    Route::post('/store', 'SubscribersController@store')->name('store_subs');
    Route::get('/list', 'SubscribersController@list')->name('subs_list');
    Route::get('/edit/{id}', 'SubscribersController@edit')->name('subs_edit');
    Route::post('/update/{id}', 'SubscribersController@update')->name('subs_update');
    Route::post('/delete/{id}', 'SubscribersController@destroy')->name('subs_delete');
    Route::get('/view/{id}', 'SubscribersController@view')->name('subs_view');
});
