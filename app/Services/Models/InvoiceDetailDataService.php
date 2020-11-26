<?php

namespace App\Services\Models;

use App\Models\InvoiceDetailData;

/**
 * 請求詳細データサービス
 * Class InvoiceDataService
 * @package App\Services\Models
 */
class InvoiceDetailDataService extends BaseService
{
    /**
     * コンストラクタ
     * InvoiceDetailDataService constructor.
     */
    public function __construct()
    {
        $this->model = new InvoiceDetailData();
    }
}
