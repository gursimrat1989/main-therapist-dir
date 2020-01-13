<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/services', function (Request $request) {
    return $request->user();
});*/

Route::get('services/list', 'ServicesApiController@servicesList');
Route::get('services/search', 'ServicesApiController@servicesSearch');
Route::post('service/add', 'ServicesApiController@addService');
Route::post('service/update', 'ServicesApiController@updateService');
Route::get('service/delete', 'ServicesApiController@deleteService');