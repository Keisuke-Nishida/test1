<?php

namespace App\Models;

use App\Lib\Constant;
use App\Models\Traits\TraitOperationUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * 管理者ユーザーModel
 * Class AdminUser
 * @package App\Models
 */
class AdminUser extends Authenticatable
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
     * 権限マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role() {
        return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }

    /**
     * 管理者権限ユーザーかどうか
     * @return bool
     */
    public function isAdmin() {
        return $this->status == Constant::STATUS_ADMIN;
    }

    /**
     * システム管理者かどうか
     * @return bool
     */
    public function isSystemAdmin() {
        return $this->system_admin_flag == Constant::SYSTEM_ADMIN;
    }
}


