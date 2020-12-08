<?php

namespace App\Services\Models;

use App\Models\Message;

/**
 * メッセージサービス
 * Class MessageService
 * @package App\Services\Models
 */
class MessageService extends BaseService
{
    /**
     * コンストラクタ
     * MessageService constructor.
     */
    public function __construct()
    {
        $this->model = new Message();
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
        $message_data = $this->searchOne($condition);
        return $message_data;
    }

}
