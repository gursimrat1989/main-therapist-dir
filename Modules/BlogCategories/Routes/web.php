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

/*oute::prefix('blogcategories')->group(function() {
    Route::get('/', 'BlogCategoriesController@index');
});*/


Route::group(['prefix'=>'admin/blog/category','middleware' => ['auth']], function () {
    Route::get('/', 'BlogCategoriesController@index')->name('blog-categories');
    Route::get('/create', 'BlogCategoriesController@create')->name('create_blog_category');
    Route::post('/store', 'BlogCategoriesController@store')->name('store_blog_category');
    Route::get('/list', 'BlogCategoriesController@list')->name('blog_category_list');
    Route::get('/edit/{id}', 'BlogCategoriesController@edit')->name('blog_category_edit');
    Route::post('/update/{id}', 'BlogCategoriesController@update')->name('blog_category_update');
    Route::post('/delete/{id}', 'BlogCategoriesController@destroy')->name('blog_category_delete');
    Route::get('/view/{id}', 'BlogCategoriesController@view')->name('blog_category_view');
});