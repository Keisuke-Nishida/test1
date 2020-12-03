<?php

namespace App\Services\Api;

use App\Services\Api\BaseApiService;
use App\Services\Models\UserService;
use App\Services\Models\UserAgreeDataService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * API用ユーザーサービス
 * Class UserService
 * @package App\Services\Api
 */
class UserApiService extends BaseApiService
{

    public function __construct()
    {
        $this->modelService = new UserService();
    }

    /**
     * getUserData
     * ログイン画面のフォームに入力されたログインIDとパスワードから
     * Userのデータを取得する
     *
     * @return Model
     */
    public function getUserData($request)
    {
        $conditions = ["login_id" => $request["login_id"]];

        $all_user_data = $this->modelService->searchList($conditions);

        // ログインIDがユニークでない場合はpasswordでの検証も必要
        foreach ($all_user_data as $data) {
            if ($this->validatePassword($request, $data->password)) {
                $user_data = $data;
            }
        }

        // ユーザーのデータが有る場合
        if (isset($user_data)) {
            return $user_data;
        }

        // ユーザーのデータが無い場合
        if (!isset($user_data)) {
            return;
        }
    }

    /**
     * validatePassword
     * getUserData内でデータを取得する前にPasswordチェック
     *
     * @param  mixed $request
     * @param  mixed $password
     * @return void
     */
    public function validatePassword($request, $password)
    {
        return Hash::check($request["password"], $password);
    }
}
