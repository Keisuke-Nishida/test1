<?php

namespace App\Http\Controllers\Web;

/**
 * Mypageコントローラー
 * Class MypageController
 * @package App\Http\Controllers\Web
 */
class MypageController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService = null;
        $this->mainRoot     = "web/mypage";
    }

    /**
     * パスワード変更ページ
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function password() {
        return view($this->mainRoot.'/password');
    }
}
