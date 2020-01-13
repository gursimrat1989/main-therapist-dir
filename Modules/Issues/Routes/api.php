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

/*Route::middleware('auth:api')->get('/issues', function (Request $request) {
    return $request->user();
});*/

Route::get('issues/list', 'IssuesApiController@issuesList');
Route::get('issues/search', 'IssuesApiController@issuesSearch');
Route::post('issue/add', 'IssuesApiController@addIssue');
Route::post('issue/update', 'IssuesApiController@updateIssue');
Route::post('issues/add/description', 'IssuesApiController@issuesDescriptionAdd');
Route::get('issues/list/description', 'IssuesApiController@issuesDescriptionList');
Route::post('issues/description/update', 'IssuesApiController@issuesDescriptionUpdate');
Route::post('issues/description/delete', 'IssuesApiController@issuesDescriptionDelete');
Route::get('issue/delete', 'IssuesApiController@deleteIssue');
Route::get('issues/get/name', 'IssuesApiController@issueGetName');