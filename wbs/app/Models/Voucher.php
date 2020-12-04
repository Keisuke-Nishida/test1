<?php

namespace App\Models;

use App\Models\Traits\TraitOperationUser;

/**
 * 伝票区分Model
 * Class Voucher
 * @package App\Models
 */
class Voucher extends BaseModel
{
    use TraitOperationUser;

    protected $table = 'voucher';

}


