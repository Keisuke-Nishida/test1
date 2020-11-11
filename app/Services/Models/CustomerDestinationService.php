<?php

namespace App\Services\Models;

use App\Models\CustomerDestination;

/**
 * 得意先送り先サービス
 * Class CustomerDestinationService
 * @package App\Services\Models
 */
class CustomerDestinationService extends BaseService
{
    /**
     * コンストラクタ
     * CustomerDestinationService constructor.
     */
    public function __construct()
    {
        $this->model = new CustomerDestination();
    }
}
