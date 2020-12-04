<?php

namespace App\Http\Controllers\Web;

/**
 * 掲示板コントローラー
 * Class BulletinBoardController
 * @package App\Http\Controllers\Web
 */
class BulletinBoardController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService = null;
        $this->mainRoot     = "web/bulletin_board";
    }
}
