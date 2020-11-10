<?php

namespace App\Http\Controllers\Web;

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
        $this->mainRoot     = "web/home";
    }
}
