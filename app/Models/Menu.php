<?php

namespace App\Models;

use App\Models\Traits\TraitOperationUser;
/**
 * メニューModel
 * Class Menu
 * @package App\Models
 */
class Menu extends BaseModel
{
    use TraitOperationUser;

    protected $table = 'menu';

    /**
     * 権限メニューリレーション
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function role_menu() {
        return $this->hasMany('App\Models\RoleMenu', 'menu_id', 'id');
    }
}


