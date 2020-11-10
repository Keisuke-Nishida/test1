<?php

Route::get('/admin', 'Admin\Auth\HomeController@index');

/************************************************
 *  管理者画面ルーティング(非ログイン)
 ************************************************/
Route::group(['prefix' => 'admin', 'middleware' => 'guest:admin'], function(){
    Route::get('/', 'Admin\Auth\LoginController@showLoginForm')->name('admin/login');
    Route::get('/login', 'Admin\Auth\LoginController@showLoginForm')->name('admin/login');
    Route::post('/login', 'Admin\Auth\LoginController@login')->name('admin/login/submit');
});


/************************************************
 *  管理者画面ルーティング(ログイン)
 ************************************************/
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function(){
    // ログアウト
    Route::get('/logout', 'Admin\Auth\LoginController@logout')->name('admin/logout');
    // TOPページ
    Route::get('/', 'Admin\HomeController@index')->name('admin/index');
    Route::get('/index', 'Admin\HomeController@index')->name('admin/index');

    /*****************************
     * サンプル管理
     *****************************/
    // 一覧画面
    Route::get('/sample', 'Admin\SampleController@index')->name('admin/sample/index');
    Route::get('/sample/index', 'Admin\SampleController@index')->name('admin/sample/index');

    // 検索処理(ajax呼び出し)
    Route::post('/sample/list/search', 'Admin\SampleController@list_search');
    // 新規登録画面
    Route::get('/sample/create', 'Admin\SampleController@create')->name('admin/sample/create');
    // 編集画面
    Route::get('/sample/edit/{id}', 'Admin\SampleController@edit')->name('admin/sample/edit');
    // 削除処理(ajax呼び出し)
    Route::get('/sample/delete/{id}', 'Admin\SampleController@delete')->name('admin/sample/delete');

    // 登録処理(post)
    Route::post('/sample/save', 'Admin\SampleController@save')->name('admin/sample/save');
});
