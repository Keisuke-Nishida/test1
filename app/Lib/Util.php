<?php
namespace App\Lib;

use Illuminate\Support\Facades\Route;

/**
 * ユーティリティクラス
 * Class Util
 */
class Util
{
    /**
     * bcrypt暗号化パスワード生成
     * @param $password
     * @return string
     */
    public static function getHashPassword($password)
    {
        return bcrypt($password);
    }

    public static function getCurrentScreen()
    {
        $uri = Route::current()->uri();
        $uri = explode('/', $uri);
        return strtolower($uri[0]);
    }

    public static function getCurrentAction()
    {
        $uri = Route::current()->uri();
        $uri = explode('/', $uri);

        if (isset($uri[1])) {
            return strtolower($uri[1]);
        }

        return 'index';
    }

    public static function isMenuItemActive($name)
    {
        $uri = self::getCurrentScreen();

        if ($uri == strtolower($name)) {
            return true;
        }

        return false;
    }
}
