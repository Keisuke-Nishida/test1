<?php

namespace App\Services\Models;

use App\Models\UserAgreeData;

/**
 * ユーザー免責同意履歴データサービス
 * Class UserAgreeDataService
 * @package App\Services\Models
 */
class UserAgreeDataService extends BaseService
{
    /**
     * コンストラクタ
     * UserAgreeDataService constructor.
     */
    public function __construct()
    {
        $this->model = new UserAgreeData();
    }
}
