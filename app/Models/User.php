<?php

namespace App\Models;

use App\Lib\Constant;
use App\Models\Traits\TraitOperationUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * 得意先ユーザーModel
 * Class User
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable;
    use TraitOperationUser;

    protected $table = 'user';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * 複数代入する属性
     * データ保存時にfill()を使用する場合はその属性をここに記載
     *
     * @var array
     */
    protected $fillable = [
        'password', 'updated_by'
    ];


    /**
     * 権限マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role() {
        return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }

    /**
     * 利用規約同意履歴
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user_agree_data() {
        return $this->hasOne('App\Models\UserAgreeData', 'user_id', 'id');
    }

    /**
     * 得意先マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer() {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }

    /**
     * 得意先権限ユーザーかどうか
     * @return bool
     */
    public function isCustomer() {
        return $this->status == Constant::STATUS_CUSTOMER;
    }

}


