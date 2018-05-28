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
	Route::post('getPid', 'AuthUserController@getPid');
	//糖包数据统计
    Route::post('getPacketStatByMonth', 'PacketStatController@getPacketStatByMonth');
// });

// 项目管理相关
/*Route::any('getSocialList', 'AdminController@getSocialList');

Route::any('getProjBasicInfo', 'AdminController@getProjBasicInfo');
Route::any('getProjBasicList', 'AdminController@getProjBasicList');
Route::post('updProjBasicInfo', 'AdminController@updProjBasicInfo');

Route::any('getProjMemberList', 'AdminController@getProjMemberList');
Route::post('addProjMember', 'AdminController@addProjMember');
Route::post('addProjIMember','AdminController@addProjIMember');
Route::post('delProjMember', 'AdminController@delProjMember');
Route::post('updProjMember', 'AdminController@updProjMember');

Route::any('getProjEventList', 'AdminController@getProjEventList');
Route::post('addProjEvent', 'AdminController@addProjEvent');
Route::post('delProjEvent', 'AdminController@delProjEvent');
Route::post('updProjEvent', 'AdminController@updProjEvent');

Route::any('getProjSocialList', 'AdminController@getProjSocialList');
Route::post('addProjSocial', 'AdminController@addProjSocial');
Route::post('delProjSocial', 'AdminController@delProjSocial');
Route::post('updProjSocial', 'AdminController@updProjSocial');


Route::post('addProjAdvisor', 'AdminController@addProjAdvisor');
Route::post('delProjAdvisor', 'AdminController@delProjAdvisor');
Route::post('updProjAdvisor', 'AdminController@updProjAdvisor');


Route::post('addProjPartner', 'AdminController@addProjPartner');
Route::post('addProjIPartner','AdminController@addProjIPartner');
Route::post('delProjPartner', 'AdminController@delProjPartner');
Route::post('updProjPartner', 'AdminController@updProjPartner');


Route::post('addProjReport', 'AdminController@addProjReport');
Route::post('delProjReport', 'AdminController@delProjReport');
Route::post('updProjReport', 'AdminController@updProjReport');


Route::post('addProjExchange','AdminController@addProjExchange');
Route::post('addProjIExchange','AdminController@addProjIExchange');
Route::post('delProjExchange','AdminController@delProjExchange');
Route::post('updProjExchange','AdminController@updProjExchange');


Route::any('getAdvList','AdminController@getAdvList');
Route::post('addProjAdvisor','AdminController@addProjAdvisor');
Route::post('addProjIAdvisor','AdminController@addProjIAdvisor');
Route::post('delProjAdvisor','AdminController@delProjAdvisor');

Route::post('delAdminDepositBox', 'AdminController@delDepositBox');
Route::any('getAdminProjDepositBoxList', 'AdminController@getProjDepositBoxList');
Route::any('getAdminProjDepositOrderList', 'AdminController@getProjDepositOrderList');
Route::any('getAdminBoxTxRecordList', 'AdminController@getBoxTxRecordList');
Route::post('confirmAdminBoxTx', 'AdminController@confirmBoxTx');
Route::post('addAdminDepositBox', 'AdminController@addDepositBox');*/
