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

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

Route::middleware('auth:api')->group(function(){
    Route::get('feeds', 'SocialController@feeds');
    Route::get('my-posts/{id}','SocialController@myPosts');
    Route::get('dash-comments/{id}', 'SocialController@CommentDash');

    Route::post('upload/{id}', 'SocialController@upload');
    Route::get('delete/{id}', 'SocialController@delete');
    Route::post('edit/{id}', 'SocialController@edit');
    Route::post('like', 'SocialController@like');
    Route::get('un-like/{id}', 'SocialController@unlike');
    Route::post('comment/{id}', 'SocialController@comment');
});
