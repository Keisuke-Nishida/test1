<?php
namespace App\Lib;

use Illuminate\Support\Facades\Auth;
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

    /**
     * Get name of screen currently displayed
     */
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

    /**
     * Get name of action of a screen currently displayed
     */
    public static function getCurrentAction()
    {
        $uri = Route::current()->uri();
        $uri = explode('/', $uri);

        if (isset($uri[2])) {
            return strtolower($uri[2]);
        }

        return 'index';
    }

    /**
     * Check if currently displayed screen matches with $name
     */
    public static function isMenuItemActive($name)
    {
        $uri = self::getCurrentScreen();

        if ($uri == strtolower($name)) {
            return true;
        }

        return false;
    }

    /**
     * Get language text by code
     */
    public static function langtext($code)
    {
        return config('languages.' . env('LANG_CODE') . '.' . $code);
    }

    /**
     * Get prefix based on logged in user's user status for permission use
     */
    public static function getUserRolePrefix($guard_name)
    {
        $type  = Auth::guard($guard_name)->user()->role->type;

        if ($type == 1) {
            return Constant::ADMIN_MENU_PREFIX;
        } elseif ($type == 2) {
            return Constant::FRONT_MENU_PREFIX;
        }
    }

    /**
     * Get allowed menus for admin site user
     */
    public static function getAdminMenus()
    {       
        $role_menus = Auth::guard('admin')->user()->role->role_menu;
        $menus = [];

        foreach ($role_menus as $role_menu) {
            $menus[] = $role_menu->menu->key;
        }

        return $menus;
    }

    /**
     * Determine if admin site user is allowed to a menu item
     */
    public static function isAdminUserAllowed($name)
    {
        return in_array($name, self::getAdminMenus());
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
