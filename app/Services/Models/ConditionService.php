<?php

namespace App\Services\Models;

use App\Models\Condition;

/**
 * 状況サービス
 * Class ConditionService
 * @package App\Services\Models
 */
class ConditionService extends BaseService
{
    /**
     * コンストラクタ
     * ConditionService constructor.
     */
    public function __construct()
    {
        $this->model = new Condition();
    }
}
