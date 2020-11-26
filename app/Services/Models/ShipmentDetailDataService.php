<?php

namespace App\Services\Models;

use App\Models\ShipmentData;

/**
 * 出荷データサービス
 * Class ShipmentDataService
 * @package App\Services\Models
 */
class ShipmentDataService extends BaseService
{
    /**
     * コンストラクタ
     * ShipmentDataService constructor.
     */
    public function __construct()
    {
        $this->model = new ShipmentDetailData();
    }
}
