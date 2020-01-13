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

/*Route::prefix('services')->group(function() {
    Route::get('/', 'ServicesController@index');
});*/


Route::group(['prefix'=>'admin/services','middleware' => ['auth']], function () {
    Route::get('/', 'ServicesController@index')->name('admin-services');
    Route::get('/create', 'ServicesController@create')->name('create_service');
    Route::post('/store', 'ServicesController@store')->name('store_service');
    Route::get('/list', 'ServicesController@list')->name('service_list');
    Route::get('/edit/{id}', 'ServicesController@edit')->name('service_edit');
    Route::post('/update/{id}', 'ServicesController@update')->name('service_update');
    Route::post('/delete/{id}', 'ServicesController@destroy')->name('service_delete');
    Route::get('/view/{id}', 'ServicesController@view')->name('service_view');
});