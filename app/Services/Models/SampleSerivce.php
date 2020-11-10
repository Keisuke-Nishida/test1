<?php
namespace App\Services\Models;
use App\Models\Sample;

/**
 * サンプルサービス
 * Class SampleService
 * @package App\Services\Models
 */
class SampleSerivce extends BaseService {
    /**
     * SampleService constructor.
     */
    public function __construct() {
        $this->model = new Sample();
    }
}
