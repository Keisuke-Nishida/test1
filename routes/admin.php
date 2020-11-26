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

    Route::get('/user', 'Admin\UserController@index')->name('admin/user');
    Route::get('/user/index', 'Admin\UserController@index')->name('admin/user/index');
    Route::post('/user/search', 'Admin\UserController@list_search')->name('admin/user/search');
    Route::get('/user/create', 'Admin\UserController@create')->name('admin/user/create');
    Route::get('/user/edit/{id}', 'Admin\UserController@edit')->name('admin/user/edit');
    Route::post('/user/save', 'Admin\UserController@save')->name('admin/user/save');
    Route::post('/user/delete_single', 'Admin\UserController@delete')->name('admin/user/delete_single');
    Route::post('/user/delete_multiple', 'Admin\UserController@deleteMultiple')->name('admin/user/delete_multiple');

    Route::get('/customer', 'Admin\CustomerController@index')->name('admin/customer');
    Route::get('/customer/index', 'Admin\CustomerController@index')->name('admin/customer/index');
    Route::post('/customer/search', 'Admin\CustomerController@list_search')->name('admin/customer/search');
    Route::get('/customer/create', 'Admin\CustomerController@create')->name('admin/customer/create');
    Route::get('/customer/edit/{id}', 'Admin\CustomerController@edit')->name('admin/customer/edit');
    Route::post('/customer/save', 'Admin\CustomerController@save')->name('admin/customer/save');
    Route::post('/customer/delete_single', 'Admin\CustomerController@delete')->name('admin/customer/delete_single');
    Route::post('/customer/delete_multiple', 'Admin\CustomerController@deleteMultiple')->name('admin/customer/delete_multiple');

    Route::get('/notice_data', 'Admin\NoticeDataController@index')->name('admin/notice_data');
    Route::get('/notice_data/index', 'Admin\NoticeDataController@index')->name('admin/notice_data/index');
    Route::post('/notice_data/search', 'Admin\NoticeDataController@list_search')->name('admin/notice_data/search');
    Route::get('/notice_data/create', 'Admin\NoticeDataController@create')->name('admin/notice_data/create');
    Route::get('/notice_data/edit/{id}', 'Admin\NoticeDataController@edit')->name('admin/notice_data/edit');
    Route::post('/notice_data/save', 'Admin\NoticeDataController@save')->name('admin/notice_data/save');
    Route::post('/notice_data/delete_single', 'Admin\NoticeDataController@delete')->name('admin/notice_data/delete_single');
    Route::post('/notice_data/delete_multiple', 'Admin\NoticeDataController@deleteMultiple')->name('admin/notice_data/delete_multiple');

    Route::get('/customer/{customer_id}/destination', 'Admin\CustomerDestinationController@indexGet')->name('admin/customer/destination');
    Route::get('/customer/{customer_id}/destination/index', 'Admin\CustomerDestinationController@indexGet')->name('admin/customer/destination/index');
    Route::post('/customer/destination/search', 'Admin\CustomerDestinationController@list_search')->name('admin/customer/destination/search');
    Route::get('/customer/{customer_id}/destination/create', 'Admin\CustomerDestinationController@create')->name('admin/customer/destination/create');
    Route::get('/customer/{customer_id}/destination/{customer_destination_id}/edit', 'Admin\CustomerDestinationController@edit')->name('admin/customer/destination/edit');
    Route::post('/customer/destination/save', 'Admin\CustomerDestinationController@save')->name('admin/customer/destination/save');
    Route::post('/customer/destination/delete_single', 'Admin\CustomerDestinationController@delete')->name('admin/customer/destination/delete_single');
    Route::post('/customer/destination/delete_multiple', 'Admin\CustomerDestinationController@deleteMultiple')->name('admin/customer/destination/delete_multiple');

});
