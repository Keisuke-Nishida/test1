<?php

namespace App\Services\Models;

use App\Models\UserAgreeData;

/**
 * ユーザー免責同意履歴データサービス
 * Class UserAgreeDataService
 * @package App\Services\Models
 */
class UserAgreeDataService extends BaseService
{
    /**
     * コンストラクタ
     * UserAgreeDataService constructor.
     */
    public function __construct()
    {
        $this->model = new UserAgreeData();
    }

    /**
     * ユーザーIDから同意履歴のデータを取得する
     *
     * @param  mixed $user_id
     * @return mixed
     */
    public function getUserAgreeData($user_id)
    {
        $conditions = ["user_id" => $user_id];
        $user_agree_data = $this->searchOne($conditions);

        return $user_agree_data;
    }

    /**
     * getUserDataFromLoginIdAndPassword()で取得したデータを使用して
     * user_agree_dataテーブルにユーザの同意履歴のデータの有無を返却する
     *
     * @param  mixed $user
     * @return bool
     */
    public function isAgreementHistory($user): bool
    {
        return $this->searchExists(["user_id" => $user->id]);
    }

    /**
     * ユーザーIDから利用規約同意時の利用規約同意データの登録と更新
     *
     * @param  mixed $user_id
     * @return array
     */
    public function saveUserAgreeData($user_id)
    {
        $user_agree_data = $this->getUserAgreeData($user_id);

        // insert
        if (empty($user_agree_data)) {
            $user_agree_data = $this->newModel();
            $user_agree_data->user_id = $user_id;
            $user_agree_data->created_by = $user_id;
        }
        //update
        $user_agree_data->agree_time = now();
        $user_agree_data->updated_by = $user_id;
        $user_agree_data->save();
    }
}
