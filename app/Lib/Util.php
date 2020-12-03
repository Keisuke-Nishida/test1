<?php

namespace App\Lib;

use Illuminate\Support\Facades\Storage;

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

    /**
     * ファイル名から拡張子取得(.付き)
     * @param $name
     * @return bool|string
     */
    public static function getExt($name)
    {
        return substr($name, strrpos($name, '.'));
    }

    /**
     * 画像URL取得
     * @param $image_file_name
     * @return string
     */
    public static function getImageUrl($image_file_name)
    {
        return $image_file_name ? Storage::url("images/" . $image_file_name) : asset('images/no_image.png');
    }

    /**
     * ファイルURL取得
     * @param $file_name
     * @return string|null
     */
    public static function getFileUrl($file_name)
    {
        return $file_name ? Storage::url("files/" . $file_name) : null;
    }
}
