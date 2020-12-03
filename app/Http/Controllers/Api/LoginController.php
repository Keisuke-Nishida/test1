<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Lib\Message;
use App\Providers\RouteServiceProvider;
use App\Services\Api\MessageApiService;
use App\Services\Api\UserAgreeDataApiService;
use App\Services\Api\UserApiService;
use App\Services\Models\UserAgreeDataService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

/**
 * API用ログインコントローラー
 * Class LoginController
 * @package App\Http\Controllers\Api
 */
class LoginController extends BaseController
{

    use AuthenticatesUsers;

    protected $userApiService;
    protected $userAgreeDataApiService;
    protected $messageApiService;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;


    /**
     * コンストラクター
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->userApiService = new UserApiService();
        $this->userAgreeDataApiService = new UserAgreeDataApiService();
        $this->messageApiService = new MessageApiService();
    }

    /**
     * beforeLogin
     * ログイン前処理
     *
     * @param  mixed $request
     * @return void
     */
    public function beforeLogin(Request $request)
    {
        // 最初にバリデーションを行う
        $this->validateLogin($request);

        // アカウントがあるかの認証を行う
        if (!$this->attemptLogin($request)) {
            return $this->sendFailedLoginResponse($request);
        }

        // ユーザーデータの取得
        $user_data = $this->userApiService->getUserData($this->credentials($request));
        // 利用規約のメッセージの取得
        $message_data = $this->messageApiService->getMessageTermsOfUseData();
        // 同意履歴の取得
        $hasAgreement = $this->userAgreeDataApiService->hasImmunityAgreement($user_data);

        // 同意履歴が存在しない場合（※初回ログイン）
        if (!$hasAgreement) {
            // 免責事項同意のモーダル表示（※メールアドレス入力欄表示）
            $response = [
                "message"   => "first_login",
                "login"     => false,
                "modal_flg" => 1,
                "message"   => $message_data->value
            ];
            return $this->success($response);
        }

        // 同意履歴が存在する場合
        if ($hasAgreement) {
            // 最終更新日時のほうが新しい日付の場合
            if ($this->isLastUpdateDateMoreRecent($request)) {
                // 免責事項同意のモーダル表示（※メールアドレス入力欄非表示）
                $response = [
                    "message"   => "update_agreement",
                    "login"     => false,
                    "modal_flg" => 2,
                    "message"   => $message_data->value
                ];
                return $this->success($response);
            }

            // 最終更新日時のほうが古い日付の場合
            if (!$this->isLastUpdateDateMoreRecent($request)) {
                // 通常のLogin処理へ進ませる
                $response = [
                    "message" => "login",
                    "login"   => true
                ];
                return $this->success($response);
            }
        }
    }

    /**
     * ログイン時バリデーション
     * @param Request $request
     * @throws ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password'        => 'required|string',
        ], [
            $this->username() . ".required" => Message::getMessage(Message::ERROR_001, ["ログインID"]),
            "password.required"             => Message::getMessage(Message::ERROR_001, ["パスワード"]),
        ]);
    }

    /**
     * validateModalForm
     * メールアドレス入力時のバリデーション
     * @param Request $request
     * @throws ValidationException
     */
    protected function validateModalForm(Request $request)
    {
        $user = $this->userApiService->getUserData($request);

        // 初回登録時はメールアドレスのバリデーションが必要
        if ($request->modal_flg == 1) {
            $this->validate($request, [
                'email'       => [
                    'required', 'string', 'email',
                    Rule::unique('user')->ignore($user->id)
                ],
                'check_agree' => 'accepted'
            ], [
                'email.required' => Message::getMessage(Message::ERROR_001, ["メールアドレス"]),
                'email.email'    => Message::getMessage(Message::ERROR_008, ["メールアドレス"]),
                'email.unique'   => Message::getMessage(Message::ERROR_010, ["メールアドレス"]),

                'check_agree.accepted' => Message::getMessage(Message::ERROR_009)
            ]);

            $response = [
                "login"     => false,
                "modal_flg" => 1,
            ];
        }

        if ($request->modal_flg == 2) {
            $this->validate($request, [
                'check_agree' => 'required'
            ], [
                'check_agree.required' => Message::getMessage(Message::ERROR_009)
            ]);

            $response = [
                "login"     => true,
                "user_id"   => $user->id,
                "modal_flg" => 2,
            ];
        }

        return $this->success($response);
    }

    /**
     * 認証エラー時メッセージ
     * @param Request $request
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [Message::getMessage(Message::ERROR_007)],
        ]);
    }

    /**
     * 認証
     * @param Request $request
     * @return mixed
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * ログインIDの名称変更
     * @return string
     */
    protected function username()
    {
        return "login_id";
    }

    /**
     * メール送信結果画面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResultEmail()
    {
        return view('web.layouts.result')->with([
            "check_result" => "resultEmail",
            "title"        => "送信確認",
            "message"      => Message::getMessage(Message::INFO_007),
        ]);
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
        $user = $this->userApiService->getUserData($request);
        $message = $this->messageApiService->getMessageTermsOfUseData();

        return $user->last_login_time < $message->updated_at;
    }

    /**
     * saveUserAgreeData
     * 利用規約同意時の利用規約同意データの登録と更新
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveUserAgreeData(Request $request)
    {
        $user_agree_data = $this->userAgreeDataApiService->getUserAgreeData($request->user_id);
        \DB::beginTransaction();
        try {
            // 新規登録
            if (empty($user_agree_data)) {
                $userAgreeDataService = new UserAgreeDataService();
                $user_agree_data = $userAgreeDataService->newModel();
                $user_agree_data->user_id = $request->user_id;
                $user_agree_data->created_by = $request->user_id;
            }
            $user_agree_data->agree_time = now();
            $user_agree_data->updated_by = $request->user_id;
            $user_agree_data->save();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            $status = 2;
            $response = [
                'message' => "同意情報を保存できませんでした",
                'error_data' => $e->getMessage()
            ];
            return $this->error($status, $response);
        }

        $response = [
            "message" => "success"
        ];
        return $this->success($response);
    }
}
