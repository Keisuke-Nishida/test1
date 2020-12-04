<?php

namespace App\Http\Controllers\Admin;

/**
 * HOMEコントローラー
 * Class HomeController
 * @package App\Http\Controllers\Page
 */
class HomeController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService = null;
        $this->mainRoot     = "admin/home";
    }

}
