<?php

namespace App\Http\Controllers\Web;

use App\Services\Models\BulletinBoardDataService;
use App\Services\Models\NoticeDataService;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * HOMEコントローラー
 * Class HomeController
 * @package App\Http\Controllers\Page
 */
class HomeController extends BaseController
{
    // 最新のお知らせと掲示板に表示する文字数制限
    protected $limit_title_length = 20;
    protected $limit_body_length  = 60;

    protected $noticeDataService;
    protected $bulletinBoardDataService;

    public function __construct(
        NoticeDataService $noticeDataService,
        BulletinBoardDataService $bulletinBoardDataService
    ) {
        parent::__construct();
        $this->mainService = null;
        $this->noticeDataService = $noticeDataService;
        $this->bulletinBoardDataService = $bulletinBoardDataService;
        $this->mainRoot    = "web/home";
    }

    /**
     * HOME
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 現在時刻の取得
        $current_time = Carbon::now();
        // 検索条件
        // 配信終了時刻より現在時刻が小さいものを取得する
        $conditions_notice["notice_data.end_time@>"] = $current_time;
        $conditions_bulletin["bulletin_board_data.end_time@>"] = $current_time;

        // ソート順
        $order = ["start_time" => "desc"];

        $data_notice = $this->noticeDataService->searchOne($conditions_notice, $order, []);
        $data_bulletin_board = $this->bulletinBoardDataService->searchOne($conditions_bulletin, $order, []);

        // TODO:請求管理と出荷管理に表示する数値の取得

        return parent::index($request)->with([
            'data_notice'   => $data_notice,
            'data_bulletin_board' => $data_bulletin_board,
            'limit_title_length' => $this->limit_title_length,
            'limit_body_length'  => $this->limit_body_length,
        ]);
    }
}
