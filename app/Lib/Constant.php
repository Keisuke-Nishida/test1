<?php
namespace App\Lib;

/**
 * 定数クラス
 * Class Constant
 */
class Constant
{

    const REMEMBER_TOKEN_TIME = 1440;               // ログイン情報保存時の有効時間(分)
    const SESSION_COOKIE_ADMIN = "auth-admin";      // 管理者用セッションクッキー名
    const SESSION_TABLE_ADMIN = "sessions_admin";   // 管理者用セッション名

    const STATUS_ADMIN = 1;         // user.status 管理者ユーザー
    const STATUS_CUSTOMER = 2;      // user.status 得意先ユーザー
    const SYSTEM_ADMIN = 1;         // user.system_admin_flag システム管理者

    const ADMIN_MENU_PREFIX = 'ADMIN_';
    const FRONT_MENU_PREFIX = 'FRONT_USER_';
    const MENU_HOME = 'HOME';
    const MENU_USER = 'USER';
    const MENU_CUSTOMER = 'CUSTOMER';
    const MENU_CUSTOMER_DESTINATION = 'CUSTOMER_DESTINATION';
    const MENU_ROLE = 'ROLE';
    const MENU_NOTICE = 'NOTICE';
    const MENU_BULLETIN_BOARD = 'BULLETIN_BOARD';
    const MENU_SHIPMENT = 'SHIPMENT';
    const MENU_INVOICE = 'INVOICE';
    const MENU_SCHEDULE = 'SCHEDULE';
    const MENU_MESSAGE = 'MESSAGE';
}
