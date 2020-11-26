<?php

namespace App\Models;

/**
 * 請求データModel
 * Class InvoiceData
 * @package App\Models
 */
class InvoiceData extends BaseModel
{
    // 操作しているユーザーIDはTableに無し
//    use TraitOperationUser;

    protected $table = 'invoice_data';

    /**
     * 得意先マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer() {
        return $this->hasOne('App\Models\Customer', 'code', 'customer_code');
    }
    /**
     * 得意先送り先マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer_destination() {
        return $this->hasOne('App\Models\CustomerDestination', 'code', 'customer_code');
    }
    /**
     * 扱便マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transport() {
        return $this->hasOne('App\Models\Transport', 'code', 'transport_type');
    }
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


