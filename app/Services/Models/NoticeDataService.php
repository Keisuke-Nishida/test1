<?php

namespace App\Services\Models;

use App\Models\NoticeData;

/**
 * お知らせデータサービス
 * Class NoticeDataService
 * @package App\Services\Models
 */
class NoticeDataService extends BaseService
{
    /**
     * コンストラクタ
     * NoticeDataService constructor.
     */
    public function __construct()
    {
        $this->model = new NoticeData();
    }
}
