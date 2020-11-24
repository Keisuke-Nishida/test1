<?php
namespace App\Lib;

use Illuminate\Support\Facades\Config;
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
        
        if (isset($uri[1])) {
            return $uri[1];
        } elseif (isset($uri[0])) {
            return $uri[0];
        }
    }

    public static function getCurrentAction()
    {
        $uri = Route::current()->uri();
        $uri = explode('/', $uri);

        if (isset($uri[2])) {
            return strtolower($uri[2]);
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

    public static function langtext($code)
    {
        return config('languages.' . env('LANG_CODE') . '.' . $code);
    }
}
