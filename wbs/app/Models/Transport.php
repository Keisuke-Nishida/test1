<?php

namespace App\Models;

use App\Models\Traits\TraitOperationUser;

/**
 * 扱便Model
 * Class Transport
 * @package App\Models
 */
class Transport extends BaseModel
{
    use TraitOperationUser;

    protected $table = 'transport';

}


