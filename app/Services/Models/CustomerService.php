<?php

namespace App\Services\Models;

use App\Models\Customer;

/**
 * 得意先マスタサービス
 * Class CustomerService
 * @package App\Services\Models
 */
class CustomerService extends BaseService
{
    /**
     * コンストラクタ
     * CustomerService constructor.
     */
    public function __construct()
    {
        $this->model = new Customer();
    }
}
