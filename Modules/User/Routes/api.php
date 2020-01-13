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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::post('user/detail', 'UserApiController@userDetail');
Route::get('users/search', 'UserApiController@usersSearch');
Route::post('user/login', 'UserApiController@userLogin');
Route::post('user/register', 'UserApiController@userRegister');
Route::post('user/create/profile', 'UserApiController@createProfile');
Route::post('user/update/profile/pic', 'UserApiController@updateProfilePic');
Route::get('user/therapist/list', 'UserApiController@therapistList');
Route::get('/user/get-by-name', 'UserApiController@getUserByName');

