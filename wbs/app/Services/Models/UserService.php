<?php
namespace App\Services\Models;
use App\Models\User;

/**
 * ユーザーサービス
 * Class UserService
 * @package App\Services\Models
 */
class UserService extends BaseService {
    /**
     * コンストラクタ
     * UserService constructor.
     */
    public function __construct() {
        $this->model = new User();
    }
}
