<?php

namespace App\Http\Controllers\Web;

/**
 * お知らせ管理コントローラー
 * Class NoticeController
 * @package App\Http\Controllers\Web
 */
class NoticeController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService = null;
        $this->mainRoot     = "web/notice";
    }
}
