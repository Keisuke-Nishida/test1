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

    /**
     * HOME
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view($this->mainRoot . '/index');
    }
}
