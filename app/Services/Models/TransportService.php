<?php

namespace App\Services\Models;

use App\Models\Transport;

/**
 * 扱便サービス
 * Class TransportService
 * @package App\Services\Models
 */
class TransportService extends BaseService
{
    /**
     * コンストラクタ
     * TransportService constructor.
     */
    public function __construct()
    {
        $this->model = new Transport();
    }
}
