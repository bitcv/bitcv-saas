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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 后台登录
Route::post('doSignin', 'AuthUserController@doSignin');
Route::post('getAuthUser', 'AuthUserController@getAuthUser');
Route::post('doSignout', 'AuthUserController@doSignout');

//超级管理员接口
// Route::group(['middleware' => 'checkAdmin'], function () {
	Route::post('getSimpleAuthUser', 'AuthUserController@getSimpleAuthUser');
	Route::post('updateAuthUser', 'AuthUserController@updateAuthUser');
	Route::post('addPacketPic', 'AuthUserController@addPacketPic');
	Route::post('getPacketPic', 'AuthUserController@getPacketPic');
	Route::post('uploadFile', 'FileController@uploadFile');
// });
