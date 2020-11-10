<?php

namespace App\Http\Controllers\Web;

/**
 * 出荷管理コントローラー
 * Class ShipmentController
 * @package App\Http\Controllers\Web
 */
class ShipmentController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService = null;
        $this->mainRoot     = "web/shipment";
    }
}
