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
     * 売上先都道府県マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sale_prefecture() {
        return $this->hasOne('App\Models\Prefecture', 'code', 'sale_prefecture_code');
    }
    /**
     * 送り先都道府県マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function destination_prefecture() {
        return $this->hasOne('App\Models\Prefecture', 'code', 'destination_prefecture_code');
    }
    /**
     * 伝票区分マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function voucher() {
        return $this->hasOne('App\Models\Voucher', 'code', 'voucher_type');
    }
}


