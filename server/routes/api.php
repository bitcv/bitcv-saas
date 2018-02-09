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

Route::post('login', 'ProjectController@login');
Route::post('signout', 'ProjectController@signout');


Route::any('uploadFile', 'FileController@uploadFile');

Route::any('getProjList', 'ProjectController@getProjList');
Route::any('getProjTopList', 'ProjectController@getProjTopList');
Route::any('getProjDetail', 'ProjectController@getProjDetail');
Route::any('addProject', 'ProjectController@addProject');
Route::any('getProjTagList', 'ProjectController@getProjTagList');
Route::any('getProjBasicInfo', 'ProjectController@getProjBasicInfo');
Route::any('getProjBasicList', 'ProjectController@getProjBasicList');
Route::any('updProjBasicInfo', 'ProjectController@updProjBasicInfo');
Route::any('delProject', 'ProjectController@delProject');

Route::any('getProjMemberList', 'ProjectController@getProjMemberList');
Route::any('addProjMember', 'ProjectController@addProjMember');
Route::any('delProjMember', 'ProjectController@delProjMember');
Route::any('updProjMember', 'ProjectController@updProjMember');

Route::any('getProjEventList', 'ProjectController@getProjEventList');
Route::any('addProjEvent', 'ProjectController@addProjEvent');
Route::any('delProjEvent', 'ProjectController@delProjEvent');
Route::any('updProjEvent', 'ProjectController@updProjEvent');

Route::any('getProjSocialList', 'ProjectController@getProjSocialList');
Route::any('addProjSocial', 'ProjectController@addProjSocial');
Route::any('delProjSocial', 'ProjectController@delProjSocial');
Route::any('updProjSocial', 'ProjectController@updProjSocial');

Route::any('getProjAdvisorList', 'ProjectController@getProjAdvisorList');
Route::any('addProjAdvisor', 'ProjectController@addProjAdvisor');
Route::any('delProjAdvisor', 'ProjectController@delProjAdvisor');
Route::any('updProjAdvisor', 'ProjectController@updProjAdvisor');

Route::any('getProjPartnerList', 'ProjectController@getProjPartnerList');
Route::any('addProjPartner', 'ProjectController@addProjPartner');
Route::any('delProjPartner', 'ProjectController@delProjPartner');
Route::any('updProjPartner', 'ProjectController@updProjPartner');

Route::any('getProjReportList', 'ProjectController@getProjReportList');
Route::any('addProjReport', 'ProjectController@addProjReport');
Route::any('delProjReport', 'ProjectController@delProjReport');
Route::any('updProjReport', 'ProjectController@updProjReport');

Route::any('getMediaList', 'ProjectController@getMediaList');
Route::any('addMedia', 'ProjectController@addMedia');
Route::any('updMedia', 'ProjectController@updMedia');
Route::any('delMedia', 'ProjectController@delMedia');
Route::any('updMedia', 'ProjectController@updMedia');

Route::any('getSocialList', 'ProjectController@getSocialList');
Route::any('addSocial', 'ProjectController@addSocial');
Route::any('updSocial', 'ProjectController@updSocial');
Route::any('delSocial', 'ProjectController@delSocial');
Route::any('updSocial', 'ProjectController@updSocial');

