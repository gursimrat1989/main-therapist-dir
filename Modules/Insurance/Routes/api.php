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

/*Route::middleware('auth:api')->get('/insurance', function (Request $request) {
    return $request->user();
});*/

Route::get('insurance/list', 'InsuranceApiController@insuranceList');
Route::get('insurance/search', 'InsuranceApiController@insuranceSearch');
Route::post('insurance/add', 'InsuranceApiController@addInsurance');
Route::post('insurance/update', 'InsuranceApiController@updateInsurance');
Route::get('insurance/delete', 'InsuranceApiController@deleteInsurance');