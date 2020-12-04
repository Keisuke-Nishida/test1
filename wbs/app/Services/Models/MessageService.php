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
}
