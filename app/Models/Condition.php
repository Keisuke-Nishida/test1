<?php

namespace App\Models;

use App\Models\Traits\TraitOperationUser;

/**
 * 状況Model
 * Class Condition
 * @package App\Models
 */
class Condition extends BaseModel
{
    use TraitOperationUser;

    protected $table = 'condition';

}


