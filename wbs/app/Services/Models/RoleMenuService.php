<?php

namespace App\Services\Models;

use App\Models\RoleMenu;

/**
 * 権限メニューサービス
 * Class RoleMenuService
 * @package App\Services\Models
 */
class RoleMenuService extends BaseService
{
    /**
     * コンストラクタ
     * RoleMenuService constructor.
     */
    public function __construct()
    {
        $this->model = new RoleMenu();
    }
}
