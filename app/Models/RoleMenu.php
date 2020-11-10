<?php

namespace App\Models;

use App\Models\Traits\TraitOperationUser;

/**
 * 権限メニューModel
 * Class RoleMenu
 * @package App\Models
 */
class RoleMenu extends BaseModel
{
    use TraitOperationUser;

    protected $table = 'role_menu';

    /**
     * 権限マスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role() {
        return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }

    /**
     * メニューマスタリレーション
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu() {
        return $this->belongsTo('App\Models\Menu', 'menu_id', 'id');
    }
}


