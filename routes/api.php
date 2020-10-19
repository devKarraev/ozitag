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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([
    'namespace' => 'Auth',
    'prefix'    => '/auth'
], function () {
    Route::post('/login', 'AuthController@login');
    Route::post('/register', 'AuthController@register');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('/logout', 'Auth\AuthController@logout');
        Route::get('/user', 'Auth\AuthController@user');
    });
});


Route::group([
    'namespace' => 'Flats',
    'prefix' => '/posts'
], function () {
    Route::get('/', 'PostsController@index');
    Route::get('/{postId}', 'PostsController@getById');
    Route::post('/update/{postId}', 'PostsController@updateById');
    Route::delete('/{postId}', 'PostsController@removeById')->where('id', '[0-9]+');;
});
