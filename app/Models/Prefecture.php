<?php

namespace App\Models;

use App\Models\Traits\TraitOperationUser;

/**
 * 都道府県Model
 * Class Prefecture
 * @package App\Models
 */
class Prefecture extends BaseModel
{

    use TraitOperationUser;

    protected $table = 'prefecture';

}


