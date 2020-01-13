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

/*Route::middleware('auth:api')->get('/blog', function (Request $request) {
    return $request->user();
});*/

Route::get('blog/search', 'BlogApiController@blogSearch');
Route::post('blog/article/add', 'BlogApiController@createBlogArticle');
Route::post('blog/category/add', 'BlogApiController@createBlogCategory');
Route::get('blog/article/list', 'BlogApiController@blogArticleList');
Route::get('blog/article/list/featured', 'BlogApiController@blogFeaturedArticleList');
Route::get('blog/category/list', 'BlogApiController@blogCategoryList');
Route::post('blog/delete', 'BlogApiController@deleteBlogArticle');