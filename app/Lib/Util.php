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
    // Methods used for Dusk unit testing
    protected $selector;
    
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

    /**
     * Methods used for Dusk unit testing
     * initiate for table row data
     */
    public function table_row_data($table_name, $row_num, $data_num)
    {
        $this->selector = "#$table_name > tbody > tr:nth-child($row_num) > td:nth-child($data_num)";
        return $this;
    }

    /**
     * Methods used for Dusk unit testing
     * initiate for table row
     */
    public function table_row($table_name, $row_num)
    {
        $this->selector = "#$table_name > tbody > tr:nth-child($row_num)";
        return $this;
    }

    /**
     * Methods used for Dusk unit testing
     * initiate for table row selected
     */
    public function table_row_selected($table_name, $row_num)
    {
        $this->selector = "#$table_name > tbody > tr:nth-child($row_num).selected";
        return $this;
    }

    /**
     * Methods used for Dusk unit testing
     * adding href tag to the selector
     */
    public function href()
    {
        $this->selector = $this->selector . " > a";
        return $this;
    }

    /**
     * Methods used for Dusk unit testing
     * return selector
     */
    public function getSelector()
    {
        return $this->selector;
    }
}
