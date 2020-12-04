<?php

namespace App\Models;

use App\Models\Traits\TraitOperationUser;

/**
 * 権限Model
 * Class Role
 * @package App\Models
 */
class Role extends BaseModel
{

    use TraitOperationUser;

    protected $table = 'role';

    /**
     * 権限メニューマスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function role_menu() {
        return $this->hasMany('App\Models\RoleMenu', 'role_id', 'id');
    }

}


