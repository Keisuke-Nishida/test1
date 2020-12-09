<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentDetailData extends BaseModel
{
    protected $table = 'shipment_detail_data';

    /**
     * shipment table data
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shipment_data()
    {
        return $this->hasOne('App\Models\ShipmentData', 'id', 'shipment_data_id');
    }

    /**
     * condition table data
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function condition()
    {
        return $this->hasOne('App\Models\Condition', 'code', 'condition_code');
    }
}
