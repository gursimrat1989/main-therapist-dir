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

Route::prefix('user')->group(function() {
    //Route::get('/', 'UserController@index');
});


Route::group(['prefix'=>'admin/user','middleware' => ['auth']], function () {
    Route::get('/', 'UserController@index')->name('admin-user');
    Route::get('/create', 'UserController@create')->name('create_user');
    Route::post('/store', 'UserController@store')->name('store_user');
    Route::get('/list', 'UserController@list')->name('users_list');
    Route::get('/edit/{id}', 'UserController@edit')->name('user_edit');
    Route::post('/update/{id}', 'UserController@update')->name('user_update');
    Route::post('/delete/{id}', 'UserController@destroy')->name('user_delete');
    Route::get('/view/{id}', 'UserController@view')->name('user_view');
});