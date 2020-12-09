<?php

namespace App\Services\Models;

use App\Lib\Constant;
use App\Models\User;
use App\Services\Models\MessageService;
use Illuminate\Support\Facades\Hash;

/**
 * ユーザーサービス
 * Class UserService
 * @package App\Services\Models
 */
class UserService extends BaseService
{
    protected $messageService;

    /**
     * コンストラクタ
     * UserService constructor.
     */
    public function __construct()
    {
        $this->model = new User();
        $this->messageService = new MessageService();
    }

    /**
     * リセットトークンの生成
     * md5(ユーザーID + システム日時)
     *
     * @param  mixed $id
     * @return string
     */
    public function generateResetToken($id)
    {
        return md5($id . now());
    }

    /**
     * リセットトークン有効期限日時の生成
     *
     * @return string
     */
    public function generateResetTokenLimitTime()
    {
        return now()->addMinutes(Constant::WEB_RESET_TOKEN_LIMIT_TIME);
    }

    /**
     * リセットトークンとリセットトークン有効期限日時の保存
     *
     * @param  mixed $user
     * @return void
     */
    public function saveResetToken($user)
    {
        \DB::beginTransaction();
        try {
            $user->reset_token = $this->generateResetToken($user->id);
            $user->reset_token_limit_time = $this->generateResetTokenLimitTime();
            $user->updated_by = $user->id;
            $user->save();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('database save error:' . $e->getMessage());
            throw new \Exception($e);
        }
    }

    /**
     * 指定のリセットトークンのユーザー情報を取得する
     *
     * @param  string $reset_token
     * @return null|mixed
     */
    public function getUserDataFromResetToken($reset_token)
    {
        // リセットトークンが存在しない場合はエラーを返す
        if (!$reset_token) {
            return null;
        }
        $conditions["reset_token"] = $reset_token;
        return $this->searchOne($conditions);
    }

    /**
     * 指定のログインIDとパスワードの
     * ユーザーのデータを取得する
     *
     * @param  mixed $request
     * @return mixed
     */
    public function getUserDataFromIdAndPassword($request)
    {
        $conditions = ["login_id" => $request["login_id"]];
        $user_list = $this->searchList($conditions);
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

    /**
     * isLastUpdateDateMoreRecent
     * フロント側のログイン時に使用
     * ユーザーマスタの最終ログイン日時(user.last_login_time)と
     * メッセージマスタの免責同意データの最終更新日時(message.updated_at)を比較する
     * 最終更新日時が新しい場合をtrueで返す
     *
     * @param  mixed $var
     * @return bool
     */
    public function isLastUpdateDateMoreRecent($request): bool
    {
        $user = $this->getUserDataFromIdAndPassword($request);
        $message = $this->messageService->getMessageTermsOfUseData();

        return $user->last_login_time < $message->updated_at;
    }

    /**
     * リセットトークンの有効期限日時より現在時刻のほうが早ければtrueを返す
     *
     * @param  mixed $user
     * @return bool
     */
    public function isEarlierThanLimitTime($user)
    {
        return now() < $user->reset_token_limit_time;
    }

    /**
     * メールのリンクから初回ログイン時にアップデートされる処理
     *
     * @param  mixed $user
     * @return void
     */
    public function updateUserDataAtLoginFromEmail($user)
    {
        \DB::beginTransaction();
        try {
            $user->last_login_time = now();
            $user->updated_by = $user->id;
            $user->save();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('database save error:' . $e->getMessage());
            throw new \Exception($e);
        }
    }

    /**
     * リセットトークンとその有効期限日時の初期化
     *
     * @param  mixed $user
     * @return void
     */
    public function initResetToken($user)
    {
        \DB::beginTransaction();
        try {
            $user->reset_token = null;
            $user->reset_token_limit_time = null;
            $user->save();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('database save error:' . $e->getMessage());
            throw new \Exception($e);
        }
    }
}
