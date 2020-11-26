<?php

namespace App\Models;

/**
 * 請求詳細データModel
 * Class InvoiceDetailData
 * @package App\Models
 */
class InvoiceDetailData extends BaseModel
{
    // 操作しているユーザーIDはTableに無し
//    use TraitOperationUser;

    protected $table = 'invoice_detail_data';

    /**
     * 請求データリレーション
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice_data() {
        return $this->belongsTo('App\Models\InvoiceData', 'invoice_data_id', 'id');
    }
    /**
     * 状況マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function condition() {
        return $this->hasOne('App\Models\Condition', 'code', 'condition_code');
    }
}


