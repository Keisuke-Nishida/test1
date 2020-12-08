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
     * getUserAgreeData
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
     * isAgreementHistory
     * getUserDataFromIdAndPassword()で取得したデータを使用して
     * user_agree_dataテーブルにユーザの同意履歴のデータの有無を返却する
     *
     * @param  mixed $user_data
     * @return bool
     */
    public function isAgreementHistory($user_data): bool
    {
        return $this->searchExists(["user_id" => $user_data->id]);
    }
}
