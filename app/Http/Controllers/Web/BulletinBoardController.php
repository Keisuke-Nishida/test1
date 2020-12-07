<?php

namespace App\Http\Controllers\Web;

use App\Services\Models\BulletinBoardDataService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * 掲示板コントローラー
 * Class BulletinBoardController
 * @package App\Http\Controllers\Web
 */
class BulletinBoardController extends BaseController
{
    // 一覧に表示する文字数制限
    protected $limit_title_length = 50;
    protected $limit_body_length = 150;

    public function __construct(BulletinBoardDataService $bulletinBoardDataService)
    {
        parent::__construct();
        $this->mainService = $bulletinBoardDataService;
        $this->mainRoot     = "web/bulletin_board";
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
        $conditions["bulletin_board_data.end_time@>"] = $current_time;

        // ソート順
        $order = ["start_time" => "desc"];

        $list = $this->mainService->searchPaginate($conditions, $order, [], $this->listPerLink);
        return parent::index($request)->with([
            'list' => $list,
            'limit_title_length' => $this->limit_title_length,
            'limit_body_length'  => $this->limit_body_length,
        ]);
    }

    /**
     * 詳細
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail($id)
    {
        $data = $this->mainService->find($id);
        return parent::detail($id)->with([
            'data' => $data,
            'limit_title_length' => $this->limit_title_length,
        ]);
    }

    /**
     * ファイルダウンロード
     *
     * @param  mixed $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($id)
    {
        $prefix = "public/files/";
        $data = $this->mainService->find($id);
        $file_name = $data->file_name;
        $file_path = $prefix . $file_name;
        $mime_type = Storage::mimeType($file_path);
        $headers = [["Content-Type" => $mime_type]];

        return Storage::download($file_path, $file_name, $headers);
    }
}
