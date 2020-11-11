<?php

namespace App\Services\Models;

use App\Models\Schedule;

/**
 * スケジュールサービス
 * Class ScheduleService
 * @package App\Services\Models
 */
class ScheduleService extends BaseService
{
    /**
     * コンストラクタ
     * ScheduleService constructor.
     */
    public function __construct()
    {
        $this->model = new Schedule();
    }
}
