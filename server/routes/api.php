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
 Route::group(['middleware' => 'checkAdmin'], function () {
	Route::post('getSimpleAuthUser', 'AuthUserController@getSimpleAuthUser');
	Route::post('updateAuthUser', 'AuthUserController@updateAuthUser');
	Route::post('addPacketPic', 'AuthUserController@addPacketPic');
	Route::post('getPacketPic', 'AuthUserController@getPacketPic');
	Route::post('uploadFile', 'FileController@uploadFile');
	Route::post('getPid', 'AuthUserController@getPid');
	Route::post('agreeItems', 'AuthUserController@agreeItems');
	//糖包数据统计
    Route::post('getPacketStatByMonth', 'PacketStatController@getPacketStatByMonth');
    Route::post('uploadFile2', 'FileController@uploadFile2');
    //余币包数据统计 -- 项目列表
    Route::post('getAdminDepositBoxList', 'PacketStatController@getAdminDepositBoxList');
    Route::post('getOrderDepositBoxList', 'PacketStatController@getOrderDepositBoxList');

     // aac 转盘统计
     Route::any('getZpstaCoin1', 'PacketStatController@staCoin1');
     Route::post('getTotalToken', 'PacketStatController@getTotalToken');

     // 资产统计
     Route::post('getAssetStat', 'PacketStatController@getAssetStat');
     Route::post('getOtcStatList', 'PacketStatController@getOtcStatList');
     Route::post('getAssetSnapShot', 'PacketStatController@getAssetSnapShot');

     // 生成链讯图片
     Route::post('genLianXunPic', 'ProjGenPicController@genLianXunPic');
     Route::post('getLianXunPicList', 'ProjGenPicController@getLianXunPicList');

     // 币币兑换统计
     Route::post('getExchangeRecords', 'PacketStatController@getExchangeRecords');
     Route::post('getExchangeStatData', 'PacketStatController@getExchangeStatData');
     Route::post('updateTokenPrice', 'PacketStatController@updateTokenPrice');
     Route::post('transferToken', 'PacketStatController@transferToken');
     // 充值记录
     Route::post('getRechargeRecord', 'PacketStatController@getRechargeRecord');
     Route::post('getMyExchange', 'PacketStatController@getMyExchange');
 });
