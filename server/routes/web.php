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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', 'Controller@test');

Route::get('/invite', 'InviteController@getInvite')->name('invite');
Route::post('/invite/vcode/{mobile}', 'InviteController@vcode');
Route::post('/invite/add', 'InviteController@add');