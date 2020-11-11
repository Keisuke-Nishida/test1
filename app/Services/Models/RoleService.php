<?php

namespace App\Services\Models;

use App\Models\Role;

/**
 * 権限サービス
 * Class RoleService
 * @package App\Services\Models
 */
class RoleService extends BaseService
{
    /**
     * コンストラクタ
     * RoleService constructor.
     */
    public function __construct()
    {
        $this->model = new Role();
    }
}
