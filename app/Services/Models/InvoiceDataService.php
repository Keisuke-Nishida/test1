<?php

namespace App\Services\Models;

use App\Models\InvoiceData;

/**
 * 請求データサービス
 * Class InvoiceDataService
 * @package App\Services\Models
 */
class InvoiceDataService extends BaseService
{
    /**
     * コンストラクタ
     * InvoiceDataService constructor.
     */
    public function __construct()
    {
        $this->model = new InvoiceData();
    }
}
