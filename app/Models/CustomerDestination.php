<?php

namespace App\Models;

use App\Models\Traits\TraitOperationUser;

/**
 * 得意先送り先Model
 * Class CustomerDestination
 * @package App\Models
 */
class CustomerDestination extends BaseModel
{
    use TraitOperationUser;

    protected $table = 'customer_destination';

    /**
     * 得意先マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer() {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }

    /**
     * belongsTo method to prefecture data
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prefecture()
    {
        return $this->belongsTo('App\Models\Prefecture', 'prefecture_id', 'id');
    }
}
