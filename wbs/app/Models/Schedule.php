<?php

namespace App\Models;

use App\Models\Traits\TraitOperationUser;

/**
 * スケジュールModel
 * Class Schedule
 * @package App\Models
 */
class Schedule extends BaseModel
{
    use TraitOperationUser;

    protected $table = 'schedule';

}


