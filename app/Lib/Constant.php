<?php
namespace App\Lib;
/**
 * 定数クラス
 * Class Constant
 */
class Constant{

    const REMEMBER_TOKEN_TIME = 1440;               // ログイン情報保存時の有効時間(分)
    const SESSION_COOKIE_ADMIN = "auth-admin";      // 管理者用セッションクッキー名
    const SESSION_TABLE_ADMIN = "sessions_admin";   // 管理者用セッション名

    const STATUS_ADMIN = 1;         // user.status 管理者ユーザー
    const STATUS_CUSTOMER = 2;      // user.status 得意先ユーザー
    const SYSTEM_ADMIN = 1;         // user.system_admin_flag システム管理者

    const WEB_PAGINATE_LINK_NUM = 5; // Web側の一覧表示にあるページネーションのリンク数
  
    const STATUS_NON_LINKED = 0;            // customer.core_system_status 非連携
    const STATUS_WAITING_FOR_LINKAGE = 1;   // customer.core_system_status 連携待ち
    const STATUS_LINKED = 2;                // customer.core_system_status 連携済み
    
    const WEB_PAGINATE_LINK_NUM = 5; // Web側の一覧表示にあるページネーションのリンク数

}
