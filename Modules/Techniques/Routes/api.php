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

/*Route::middleware('auth:api')->get('/techniques', function (Request $request) {
    return $request->user();
});*/

Route::get('techniques/list', 'TechniquesApiController@techniquesList');
Route::get('techniques/search', 'TechniquesApiController@techniquesSearch');
Route::post('technique/add', 'TechniquesApiController@addTechnique');
Route::post('technique/update', 'TechniquesApiController@updateTechnique');
Route::post('technique/add/description', 'TechniquesApiController@techniqueDescriptionAdd');
Route::get('technique/list/description', 'TechniquesApiController@techniqueDescriptionList');
Route::post('techniques/description/update', 'TechniquesApiController@techniquesDescriptionUpdate');
Route::post('techniques/description/delete', 'TechniquesApiController@techniquesDescriptionDelete');
Route::get('technique/delete', 'TechniquesApiController@deleteTechnique');
Route::get('technique/get/name', 'TechniquesApiController@techniqueGetName');

