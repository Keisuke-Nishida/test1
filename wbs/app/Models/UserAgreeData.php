<?php

namespace App\Models;

use App\Models\Traits\TraitOperationUser;

/**
 * ユーザー免責同意履歴データModel
 * Class UserAgreeData
 * @package App\Models
 */
class UserAgreeData extends BaseModel
{
    use TraitOperationUser;

    protected $table = 'user_agree_data';

    /**
     * ユーザーマスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}


