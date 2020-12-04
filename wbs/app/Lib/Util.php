<?php
namespace App\Lib;
/**
 * ユーティリティクラス
 * Class Util
 */
class Util {

    /**
     * bcrypt暗号化パスワード生成
     * @param $password
     * @return string
     */
    public static function getHashPassword($password) {
        return bcrypt($password);
    }
}
