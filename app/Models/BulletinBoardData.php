<?php

namespace App\Models;

use App\Models\Traits\TraitOperationUser;

/**
 * 掲示板データModel
 * Class BulletinBoardData
 * @package App\Models
 */
class BulletinBoardData extends BaseModel
{
    use TraitOperationUser;

    protected $table = 'bulletin_board_data';

}


