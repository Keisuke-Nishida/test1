<?php
namespace App\Services\Models;
use App\Models\AdminUser;

/**
 * 管理者ユーザーサービス
 * Class AdminUserService
 * @package App\Services\Models
 */
class AdminUserService extends BaseService {
    /**
     * コンストラクタ
     * AdminUserService constructor.
     */
    public function __construct() {
        $this->model = new AdminUser();
    }
}
