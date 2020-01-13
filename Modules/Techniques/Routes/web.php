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

Route::prefix('techniques')->group(function() {
    Route::get('/', 'TechniquesController@index');
});


Route::group(['prefix'=>'admin/techniques','middleware' => ['auth']], function () {
    Route::get('/', 'TechniquesController@index')->name('techniques');
    Route::get('/create', 'TechniquesController@create')->name('create_technique');
    Route::post('/store', 'TechniquesController@store')->name('store_technique');
    Route::get('/list', 'TechniquesController@list')->name('techniques_list');
    Route::get('/edit/{id}', 'TechniquesController@edit')->name('technique_edit');
    Route::post('/update/{id}', 'TechniquesController@update')->name('technique_update');
    Route::post('/delete/{id}', 'TechniquesController@destroy')->name('technique_delete');
    Route::get('/view/{id}', 'TechniquesController@view')->name('technique_view');
});