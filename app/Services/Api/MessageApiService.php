<?php

namespace App\Services\Api;

use App\Services\Api\BaseApiService;
use App\Services\Models\MessageService;
use Illuminate\Database\Eloquent\Model;

/**
 * API用ログインサービス
 * Class MessageApiService
 * @package App\Services\Api
 */
class MessageApiService extends BaseApiService
{

    public function __construct()
    {
        $this->modelService = new MessageService();
    }

    /**
     * getMessageData
     * 最新の利用規約のデータを取得
     * @return mixed
     */
    public function getMessageTermsOfUseData()
    {
        // TODO: Lib/Constant.phpで利用規約用の定数が定義されたら修正する
        $condition = ["key" => 1];
        $message_data = $this->modelService->searchOne($condition);
        return $message_data;
    }
}
