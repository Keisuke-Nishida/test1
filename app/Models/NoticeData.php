<?php

namespace App\Models;

use App\Models\Traits\TraitOperationUser;

/**
 * お知らせデータModel
 * Class InvoiceData
 * @package App\Models
 */
class NoticeData extends BaseModel
{
    use TraitOperationUser;

    protected $table = 'notice_data';

}


