<?php

namespace App\Services\Models;

use App\Models\Voucher;

/**
 * 伝票区分サービス
 * Class VoucherService
 * @package App\Services\Models
 */
class VoucherService extends BaseService
{
    /**
     * コンストラクタ
     * VoucherService constructor.
     */
    public function __construct()
    {
        $this->model = new Voucher();
    }
}
