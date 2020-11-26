<?php

namespace App\Models;

/**
 * 出荷詳細データModel
 * Class ShipmentDetailData
 * @package App\Models
 */
class ShipmentDetailData extends BaseModel
{

    // 操作しているユーザーIDはTableに無し
//    use TraitOperationUser;

    protected $table = 'shipment_detail_data';

    /**
     * 出荷データリレーション
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipment_data() {
        return $this->belongsTo('App\Models\ShipmentData', 'shipment_data_id', 'id');
    }
    /**
     * 扱便マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transport() {
        return $this->hasOne('App\Models\Transport', 'code', 'transport_type');
    }
    /**
     * 状況マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function condition() {
        return $this->hasOne('App\Models\Condition', 'code', 'condition_code');
    }
}


