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

/*Route::prefix('issues')->group(function() {
    Route::get('/', 'IssuesController@index');
});*/

Route::group(['prefix'=>'admin/issues','middleware' => ['auth']], function () {
    Route::get('/', 'IssuesController@index')->name('issues');
    Route::get('/create', 'IssuesController@create')->name('create_issue');
    Route::post('/store', 'IssuesController@store')->name('store_issue');
    Route::get('/list', 'IssuesController@list')->name('issues_list');
    Route::get('/edit/{id}', 'IssuesController@edit')->name('issue_edit');
    Route::post('/update/{id}', 'IssuesController@update')->name('issue_update');
    Route::post('/delete/{id}', 'IssuesController@destroy')->name('issue_delete');
    Route::get('/view/{id}', 'IssuesController@view')->name('issue_view');
});
