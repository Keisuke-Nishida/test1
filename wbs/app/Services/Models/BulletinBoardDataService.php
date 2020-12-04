<?php

namespace App\Services\Models;

use App\Models\BulletinBoardData;

/**
 * 掲示板データサービス
 * Class BulletinBoardDataService
 * @package App\Services\Models
 */
class BulletinBoardDataService extends BaseService
{
    /**
     * コンストラクタ
     * BulletinBoardDataService constructor.
     */
    public function __construct()
    {
        $this->model = new BulletinBoardData();
    }
}
