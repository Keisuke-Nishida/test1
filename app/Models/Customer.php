<?php

namespace App\Models;

use App\Models\Traits\TraitOperationUser;

/**
 * 得意先マスタModel
 * Class Customer
 * @package App\Models
 */
class Customer extends BaseModel
{
    use TraitOperationUser;

    protected $table = 'customer';

    /**
     * 都道府県マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prefecture() {
        return $this->belongsTo('App\Models\Prefecture', 'prefecture_id', 'id');
    }

    /**
     * ユーザーマスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user() {
        return $this->hasMany('App\Models\User', 'customer_id', 'id');
    }

    /**
     * 得意先送り先マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customer_destination() {
        return $this->hasMany('App\Models\CustomerDestination', 'customer_id', 'id');
    }
}


