<?php

namespace App\Models;

use App\Models\Traits\TraitOperationUser;

/**
 * サンプルModel
 * Class Sample
 * @package App\Models
 */
class Sample extends BaseModel
{
    use TraitOperationUser;

    protected $table = 'sample';

}


