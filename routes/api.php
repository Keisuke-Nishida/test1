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

/*************************************************
 *  API(認証前)
 *************************************************/
// ログイン
Route::post('/login',   'Api\LoginController@login');
// ログイン前処理
Route::post('/beforeLogin',   'Api\LoginController@beforeLogin');
// 利用規約モーダルのバリデーション
Route::post('/validateModalForm',   'Api\LoginController@validateModalForm');
// 利用規約の同意日時の更新
Route::post('/saveUserAgreeData',   'Api\LoginController@saveUserAgreeData');
// Email送信結果ページ
Route::get('/resultEmail', 'Api\LoginController@showResultEmail')->name('result');


/*************************************************
 *  API(ログイン処理・トークン発行)
 *************************************************/
// Route::group(['middleware' => 'guest:web'], function () {
//     Route::post('/validate_login',   'Api\LoginController@login');
// });


/*************************************************
 *  API(認証済み)
 *************************************************/
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
