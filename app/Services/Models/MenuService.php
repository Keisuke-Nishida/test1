<?php

namespace App\Services\Models;

use App\Models\Menu;

/**
 * メニューサービス
 * Class MenuService
 * @package App\Services\Models
 */
class MenuService extends BaseService
{
    /**
     * コンストラクタ
     * MenuService constructor.
     */
    public function __construct()
    {
        $this->model = new Menu();
    }
}
