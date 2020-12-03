<?php

namespace App\Services\Api;

use App\Services\Models\UserAgreeDataService;
use Illuminate\Database\Eloquent\Model;

/**
 * API用ユーザー免責同意履歴データサービス
 * Class UserAgreeDataApiService
 * @package App\Services\Api
 */
class UserAgreeDataApiService extends BaseApiService
{

    protected $userApiService;

    public function __construct()
    {
        $this->modelService = new UserAgreeDataService();

        $this->userApiService = new UserApiService();
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
        $user_agree_data = $this->modelService->searchOne($conditions);

        return $user_agree_data;
    }

    /**
     * hasImmunityAgreement
     * getUserData()で取得したデータを使用して
     * user_agree_dataテーブルにユーザの同意履歴のデータの有無を返却する
     *
     * @param  mixed $user_data
     * @return bool
     */
    public function hasImmunityAgreement($user_data): bool
    {
        $has_agreement = $this->modelService->searchOne(["user_id" => $user_data->id]);

        return isset($has_agreement);
    }
}
