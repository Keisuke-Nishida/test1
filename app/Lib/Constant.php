<?php
namespace App\Lib;
/**
 * 定数クラス
 * Class Constant
 */
class Constant {

    const REMEMBER_TOKEN_TIME = 1440;               // ログイン情報保存時の有効時間(分)
    const SESSION_COOKIE_ADMIN = "auth-admin";      // 管理者用セッションクッキー名
    const SESSION_TABLE_ADMIN = "sessions_admin";   // 管理者用セッション名

    const STATUS_ADMIN = 1;         // user.status 管理者ユーザー
    const STATUS_CUSTOMER = 2;      // user.status 得意先ユーザー
    const SYSTEM_ADMIN = 1;         // user.system_admin_flag システム管理者

}
