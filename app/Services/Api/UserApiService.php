<?php

namespace App\Services\Api;

use App\Services\Api\BaseApiService;
use App\Services\Models\UserService;
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
     * @param  mixed $request
     * @return mixed
     */
    public function getUserData($request)
    {
        $conditions = ["login_id" => $request["login_id"]];
        $user_list = $this->modelService->searchList($conditions);
        $user_data = NULL;

        // ログインIDがユニークでない場合はpasswordでの検証も必要
        foreach ($user_list as $user) {
            if ($this->validatePassword($request, $user->password)) {
                $user_data = $user;
            }
        }

        return $user_data;
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
