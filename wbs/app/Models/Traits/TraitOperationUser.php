<?php

namespace App\Models\Traits;

/**
 * 操作者リレーションTrait
 * Trait TraitOperationUser
 * @package App\Models\Traits
 */
trait TraitOperationUser {
    /**
     * 作成者リレーション
     * @return mixed
     */
    public function create_user() {
        return $this->belongsTo('App\Models\User', 'created_by', 'id')->withDefault([
            'name' => ''
        ]);
    }
    /**
     * 更新者リレーション
     * @return mixed
     */
    public function update_user() {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id')->withDefault([
            'name' => ''
        ]);
    }
    /**
     * 削除者リレーション
     * @return mixed
     */
    public function delete_user() {
        return $this->belongsTo('App\Models\User', 'deleted_by', 'id')->withDefault([
            'name' => ''
        ]);
    }

}
