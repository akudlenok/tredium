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

Route::post('/articles/{id}/like', 'ArticleController@like');
Route::post('/articles/{id}/viewed', 'ArticleController@viewed');
Route::get('/articles/{id}', 'ArticleController@show');
Route::get('/articles', 'ArticleController@index');

Route::post('/articles/comments', 'ArticleCommentController@store');

Route::get('/articles/tags/{id}', 'ArticleTagController@show');
