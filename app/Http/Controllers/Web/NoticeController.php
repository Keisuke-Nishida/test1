<?php

namespace App\Http\Controllers\Web;

use App\Services\Models\NoticeDataService;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * お知らせ管理コントローラー
 * Class NoticeController
 * @package App\Http\Controllers\Web
 */
class NoticeController extends BaseController
{
    // 一覧に表示する文字数制限
    protected $limit_title_length = 60;
    protected $limit_body_length = 200;

    public function __construct(NoticeDataService $noticeDataService)
    {
        parent::__construct();
        $this->mainService = $noticeDataService;
        $this->mainRoot    = "web/notice";
    }

    /**
     * 一覧画面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 現在時刻の取得
        $current_time = Carbon::now();
        // 検索条件
        // 配信終了時刻より現在時刻が小さいものを取得する
        $conditions["notice_data.end_time@>"] = $current_time;

        // ソート順
        $order = ["start_time" => "desc"];

        $list = $this->mainService->searchPaginate($conditions, $order, [], $this->listPerLink);
        return parent::index($request)->with([
            'list' => $list,
            'limit_title_length' => $this->limit_title_length,
            'limit_body_length'  => $this->limit_body_length,
        ]);
    }
}
