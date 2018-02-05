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


Route::get('/', 'ProjController@index');

//saas.lianbi.io saas申请和审核
Route::group(['prefix' => 'saas'], function() {
    
    //项目方申请
    Route::get('apply', 'ProjController@apply')->name('proj.apply');

    //管理后台
    Route::get('login', 'SaasController@loginView')->name('saas.loginView');
    Route::post('login', 'SaasController@login')->name('saas.login');
    //需要管理员登录
    Route::group(['middleware' => \App\Http\Middleware\SaasLogin::class], function() {
        Route::get('admin', 'SaasController@projs')->name('saas.admin');
        Route::get('proj/{projid}', 'SaasController@proj')->name('saas.proj.detail');
        Route::post('audit/{projid}', 'SaasController@audit')->name('saas.proj.audit');
    });
});


//应用，收集钱包地址邀请
Route::prefix('invite', function() {
    Route::get('/', 'InviteController@getInvite')->name('invite');
    Route::post('vcode/{mobile}', 'InviteController@vcode');
    Route::post('add', 'InviteController@add');
    Route::post('verifyCode', 'InviteController@verifyCode');
});
