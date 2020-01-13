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

/*Route::prefix('blog')->group(function() {
    Route::get('/', 'BlogController@index');
});*/


Route::group(['prefix'=>'admin/blog/article','middleware' => ['auth']], function () {
    Route::get('/', 'BlogController@index')->name('articles');
    Route::get('/create', 'BlogController@create')->name('create_article');
    Route::post('/store', 'BlogController@store')->name('store_article');
    Route::get('/list', 'BlogController@list')->name('articles_list');
    Route::get('/edit/{id}', 'BlogController@edit')->name('article_edit');
    Route::post('/update/{id}', 'BlogController@update')->name('article_update');
    Route::post('/delete/{id}', 'BlogController@destroy')->name('article_delete');
    Route::get('/view/{id}', 'BlogController@view')->name('article_view');
});