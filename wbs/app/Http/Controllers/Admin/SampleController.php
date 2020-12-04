<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Message;
use App\Services\Models\SampleSerivce;
use Illuminate\Http\Request;

/**
 * サンプルコントローラー
 * Class SampleController
 * @package App\Http\Controllers\Admin
 */
class SampleController extends BaseController
{

    public function __construct(SampleSerivce $sampleSerivce)
    {
        parent::__construct();
        $this->mainService  = $sampleSerivce;
        $this->mainRoot     = "admin/sample";
        $this->mainTitle    = "サンプル";
    }

    /**
     * 検索条件設定
     * @param Request $request
     * @return array
     */
    public function list_search_condition(Request $request) {
        $condition = [];
        // 名前条件
        if ($request->name) {
            $condition = ['name@like' => $request->name];
        }

        return [
            'condition' => $condition,
            'sort'      => [],
            'relation'  => ['update_user'],
        ];
    }

    /**
     * バリデーションルール
     * @param Request $request
     * @return array|void
     */
    public function validation_rules(Request $request) {
        return [
            'name'      => 'required',
            'sample1'   => 'required|max:20',
        ];
    }

    /**
     * バリデーションメッセージ
     * @param Request $request
     * @return array|void
     */
    public function validation_message(Request $request) {
        return [
            'name.required' => Message::getMessage(Message::ERROR_001, ['名前']),
            'sample1.required' => Message::getMessage(Message::ERROR_001, ['サンプル１']),
            'sample1.max'   => Message::getMessage(Message::ERROR_002, ['サンプル１', "20"]),
        ];
    }
}
