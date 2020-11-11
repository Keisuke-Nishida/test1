<?php

namespace App\Services\Models;

use App\Models\Prefecture;

/**
 * 都道府県サービス
 * Class PrefectureService
 * @package App\Services\Models
 */
class PrefectureService extends BaseService
{
    /**
     * コンストラクタ
     * PrefectureService constructor.
     */
    public function __construct()
    {
        $this->model = new Prefecture();
    }
}
