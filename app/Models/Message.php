<?php

namespace App\Models;

use App\Models\Traits\TraitOperationUser;

/**
 * メッセージModel
 * Class Message
 * @package App\Models
 */
class Message extends BaseModel
{
    use TraitOperationUser;

    protected $table = 'message';

}


