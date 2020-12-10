<?php

namespace App\Services\Models;

use App\Lib\Constant;
use App\Models\User;
use App\Services\Models\MessageService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

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
     * ログインフォームに入力されたログインIDとパスワードから
     * ユーザーのデータを取得する
     *
     * @param  Request $request
     * @return mixed
     */
    public function getUserDataFromLoginIdAndPassword(Request $request)
    {
        $conditions = ["login_id" => $request->login_id];
        $user_list = $this->searchList($conditions);
        $user_data = NULL;

        // ログインIDがユニークでない場合はpasswordでの検証も必要
        foreach ($user_list as $user) {
            if ($this->validatePassword($request->password, $user->password)) {
                $user_data = $user;
            }
        }

        return $user_data;
    }

    /**
     * ログインフォームに入力されたパスワードと
     * ユーザーマスタに登録されたパスワードが合っているかのチェック
     *
     * @param  mixed $password
     * @param  mixed $password_hash
     * @return void
     */
    public function validatePassword($password, $password_hash)
    {
        return Hash::check($password, $password_hash);
    }

    /**
     * ログインフォームの入力からユーザーを取得し、
     * ユーザーマスタの最終ログイン日時(user.last_login_time)と
     * メッセージマスタの免責同意データの最終更新日時(message.updated_at)を比較する
     * 最終更新日時が新しい場合をtrueで返す
     *
     * @param  Request $request
     * @return bool
     */
    public function isLastUpdateDateMoreRecent(Request $request): bool
    {
        $user = $this->getUserDataFromLoginIdAndPassword($request);
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
        // 有効期限がなかった場合はfalseを返す
        if (!$user->reset_token_limit_time) {
            return false;
        }

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
        $user->last_login_time = now();
        $user->updated_by = $user->id;
        $user->save();
    }

    /**
     * リセットトークンとその有効期限日時の初期化
     *
     * @param  mixed $user
     * @return void
     */
    public function initResetToken($user)
    {
        $user->reset_token = null;
        $user->reset_token_limit_time = null;
        $user->save();
    }
}
