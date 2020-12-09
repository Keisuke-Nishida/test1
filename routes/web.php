<?php


/************************************************
 *  フロントサイドルーティング(非ログイン)
 ************************************************/
Route::group(['middleware' => 'guest:web'], function () {
    // ログイン
    Route::get('/', 'Web\Auth\LoginController@showLoginForm')->name('login');
    Route::get('/login', 'Web\Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Web\Auth\LoginController@login')->name('login/submit');

    // Emailからの利用登録確認処理
    Route::get('/register/{reset_token}', 'Web\Auth\LoginController@loginFromEmail');

    // パスワードを忘れた場合
    // Route::get('/password_reset');
});

/************************************************
 *  フロントサイドルーティング(ログイン)
 ************************************************/
Route::group(['middleware' => 'auth:web'], function () {
    // ログアウト
    Route::get('/logout', 'Web\Auth\LoginController@logout')->name('logout');

    // TOPページ
    Route::get('/', 'Web\HomeController@index')->name('index');
    Route::get('/index', 'Web\HomeController@index')->name('index');

    // 出荷管理
    Route::get('/shipment', 'Web\ShipmentController@index')->name('shipment/index');
    Route::get('/shipment/detail/{id}', 'Web\ShipmentController@detail')->name('shipment/detail');

    // 請求管理
    Route::get('/invoice', 'Web\InvoiceController@index')->name('invoice/index');
    Route::get('/invoice/detail/{id}', 'Web\InvoiceController@detail')->name('invoice/detail');

    // お知らせ
    Route::get('/notice', 'Web\NoticeController@index')->name('notice/index');
    Route::get('/notice/detail/{id}', 'Web\NoticeController@detail')->name('notice/detail');

    // 掲示板
    Route::get('/bulletin_board', 'Web\BulletinBoardController@index')->name('bulletin_board/index');
    Route::get('/bulletin_board/detail/{id}', 'Web\BulletinBoardController@detail')->name('bulletin_board/detail');
    Route::get('/bulletin_board/download/{id}', 'Web\BulletinBoardController@download')->name('bulletin_board/download');

    // パスワード変更
    Route::get('/mypage/password', 'Web\MypageController@password')->name('mypage/password');
    Route::post('/mypage/password/changePassword', 'Web\MypageController@changePassword')->name('mypage/password/changePassword');
});
