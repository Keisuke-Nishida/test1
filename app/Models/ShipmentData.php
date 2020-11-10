<?php

namespace App\Models;

/**
 * 出荷データModel
 * Class ShipmentData
 * @package App\Models
 */
class ShipmentData extends BaseModel
{

    // 操作しているユーザーIDはTableに無し
//    use TraitOperationUser;

    protected $table = 'shipment_data';

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
            return $this->hasOne('App\Models\Condition', 'code', 'condition_type');
    }
    /**
     * 都道府県マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function prefecture() {
        return $this->hasOne('App\Models\Prefecture', 'code', 'sale_prefecture_code');
    }
    /**
     * 伝票区分マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function voucher() {
        return $this->hasOne('App\Models\Voucher', 'code', 'voucher_type');
    }
}


