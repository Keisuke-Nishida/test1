<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 管理画面用Baseコントローラー
 * Class BaseController
 * @package App\Http\Controllers\Admin
 */
class BaseController extends Controller
{
    // メインサービス
    protected $mainService;
    // メインルート
    protected $mainRoot;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        // 子クラスは、メインサービス・ルートを子クラスで定義する
    }

    /**
     * バリデーションルール　※オーバーライドして使用する
     * @param Request $request
     * @return array
     */
    public function validation_rules(Request $request) {
        return [];
    }

    /**
     * バリデーションメッセージ　※オーバーライドして使用する
     * @param Request $request
     * @return array
     */
    public function validation_message(Request $request) {
        return [];
    }

    /**
     * HOME
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view($this->mainRoot.'/index');
    }
}
