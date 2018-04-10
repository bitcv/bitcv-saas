<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'ProjController@apply');
//Route::get('/', 'InviteController@getInvite')->name('invite');

//saas.bitcv.com saas申请和审核
Route::group(['prefix' => 'saas'], function() {
    
    //项目方申请
    Route::get('apply', 'ProjController@apply')->name('proj.apply');
    Route::post('add', 'ProjController@add')->name('proj.add');

    //管理后台
    Route::get('login', 'SaasController@loginView')->name('saas.loginView');
    Route::post('login', 'SaasController@login')->name('saas.login');
    Route::get('logout', 'SaasController@logout')->name('saas.logout');
    //需要管理员登录
    Route::group(['middleware' => \App\Http\Middleware\SaasLogin::class], function() {
        Route::get('admin', 'SaasController@projs')->name('saas.admin');
        Route::get('module/{projid}', 'SaasController@module')->name('saas.module');
        Route::post('audit}', 'SaasController@audit')->name('saas.proj.audit');
        Route::post('auditmod}', 'SaasController@auditMod')->name('saas.proj.mod');
        Route::post('addmod', 'SaasController@add')->name('saas.proj.add');
    });
});

Route::group(['prefix' => 'admin'], function() {
    Route::get('/', 'ProjectController@admin');
});



//应用，收集钱包地址邀请
Route::group(['prefix' => 'invite'], function() {
    Route::get('/', 'InviteController@getInvite')->name('invite');
    Route::post('vcode/{mobile}', 'InviteController@vcode');
    Route::post('add', 'InviteController@add');
    Route::post('verifyCode', 'InviteController@verifyCode');
    Route::post('stat', 'InviteController@stat');
});

//eth
Route::group(['prefix' => 'eth'], function() {
    Route::get('/', 'EthController@tran');
});
