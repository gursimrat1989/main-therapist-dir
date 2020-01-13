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

/*Route::prefix('insurance')->group(function() {
    Route::get('/', 'InsuranceController@index');
});*/

Route::group(['prefix'=>'admin/insurance','middleware' => ['auth']], function () {
    Route::get('/', 'InsuranceController@index')->name('insurance');
    Route::get('/create', 'InsuranceController@create')->name('create_insurance');
    Route::post('/store', 'InsuranceController@store')->name('store_insurance');
    Route::get('/list', 'InsuranceController@list')->name('insurance_list');
    Route::get('/edit/{id}', 'InsuranceController@edit')->name('insurance_edit');
    Route::post('/update/{id}', 'InsuranceController@update')->name('insurance_update');
    Route::post('/delete/{id}', 'InsuranceController@destroy')->name('insurance_delete');
    Route::get('/view/{id}', 'InsuranceController@view')->name('insurance_view');
});
