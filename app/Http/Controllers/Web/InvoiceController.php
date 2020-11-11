<?php

namespace App\Http\Controllers\Web;

/**
 * 請求管理コントローラー
 * Class InvoiceController
 * @package App\Http\Controllers\Web
 */
class InvoiceController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService = null;
        $this->mainRoot     = "web/invoice";
    }
}
