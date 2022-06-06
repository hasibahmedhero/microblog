<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/get-posts', 'Posts\PostsController@getPosts');
Route::post('/get-post-details/{postId}', 'Posts\PostsController@getPostDetails');

Route::group(['prefix' => 'auth'], function() {
	Route::post('/requestOtp', 'Auth\AuthController@requestOtp');
	Route::post('/verify', 'Auth\AuthController@verify');
	Route::post('/logout', 'Auth\AuthController@logout');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
	
	Route::group(['prefix' => 'my-account'], function() {
		Route::post('/create-post', 'MyAccount\PostsController@createPost');
		Route::post('/get-posts', 'MyAccount\PostsController@getPosts');
		Route::post('/delete-post', 'MyAccount\PostsController@deletePost');
		Route::post('/post-comment', 'MyAccount\PostsController@postComment');
		Route::post('/post-vote', 'MyAccount\PostsController@postVote');
	});
	
});