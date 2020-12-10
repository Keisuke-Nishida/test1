<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Lib\Message;
use App\Providers\RouteServiceProvider;
use App\Services\Models\CustomerService;
use App\Services\Models\MessageService;
use App\Services\Models\UserAgreeDataService;
use App\Services\Models\UserService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

/**
 * API用ログインコントローラー
 * Class LoginApiController
 * @package App\Http\Controllers\Api
 */
class LoginApiController extends BaseApiController
{

    use AuthenticatesUsers;

    protected $userService;
    protected $userAgreeDataService;
    protected $messageService;
    protected $customerService;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;


    /**
     * コンストラクター
     * LoginApiController constructor.
     */
    public function __construct(
        UserService $userService,
        UserAgreeDataService $userAgreeDataService,
        MessageService $messageService,
        CustomerService $customerService
    ) {
        $this->userService = $userService;
        $this->userAgreeDataService = $userAgreeDataService;
        $this->messageService = $messageService;
        $this->customerService = $customerService;
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
        $user = $this->userService->getUserDataFromLoginIdAndPassword($request);
        // 利用規約のメッセージの取得
        $message = $this->messageService->getMessageTermsOfUseData();
        // 同意履歴の取得
        $is_agreement_history = $this->userAgreeDataService->isAgreementHistory($user);

        // 同意履歴が存在しない場合（※初回ログイン）
        if (!$is_agreement_history) {
            // 免責事項同意のモーダル表示（※メールアドレス入力欄表示）
            $response = [
                "status"   => "first_login",
                "login"     => false,
                "message"   => $message->value
            ];
            return $this->success($response);
        }

        // 同意履歴が存在する場合
        if ($is_agreement_history) {
            $is_more_recent = $this->userService->isLastUpdateDateMoreRecent($request);

            // 最終ログイン日時より最終更新日時のほうが新しい日付の場合
            if ($is_more_recent) {
                // 免責事項同意のモーダル表示（※メールアドレス入力欄非表示）
                $response = [
                    "status"   => "update_agreement",
                    "login"     => false,
                    "message"   => $message->value
                ];
                return $this->success($response);
            }

            // 最終更新日時のほうが古い日付の場合
            if (!$is_more_recent) {
                // 通常のLogin処理へ進ませる
                $response = [
                    "status" => "login",
                    "login"   => true
                ];
                return $this->success($response);
            }
        }
    }

    /**
     * ログインフォームのバリデーション
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
        $user = $this->userService->getUserDataFromLoginIdAndPassword($request);

        // 初回登録時はメールアドレスのバリデーションが必要
        if ($request->status == "first_login") {
            $this->validateFirstLoginAgreement($request, $user->id);

            // バリデーション成功時、user.reset_tokenとuser.reset_token_limit_timeを登録する
            $this->userService->saveResetToken($user);

            $response = [
                "login"  => false,
                "status" => "first_login",
            ];
        }

        // 同意更新時
        if ($request->status == "update_agreement") {
            $this->validateUpdateAgreement($request);
            $response = [
                "login"   => true,
                "user_id" => $user->id,
                "status"  => "update_agreement",
            ];
        }

        return $this->success($response);
    }

    /**
     * 初回ログイン時に表示されるモーダルのバリデーション
     *
     * @param  mixed $request
     * @param  mixed $user_id
     * @return void
     */
    protected function validateFirstLoginAgreement(Request $request, $user_id)
    {
        $this->validate($request, [
            'email'       => [
                'required', 'string', 'email',
                Rule::unique('user')->ignore($user_id)
            ],
            'check_agree' => 'accepted'
        ], [
            'email.required' => Message::getMessage(Message::ERROR_001, ["メールアドレス"]),
            'email.email'    => Message::getMessage(Message::ERROR_008, ["メールアドレス"]),
            'email.unique'   => Message::getMessage(Message::ERROR_010, ["メールアドレス"]),

            'check_agree.accepted' => Message::getMessage(Message::ERROR_009)
        ]);
    }

    /**
     * 同意履歴更新時に表示されるモーダルのバリデーション
     *
     * @param  mixed $request
     * @return void
     */
    protected function validateUpdateAgreement(Request $request)
    {
        $this->validate($request, [
            'check_agree' => 'required'
        ], [
            'check_agree.required' => Message::getMessage(Message::ERROR_009)
        ]);
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
            "isLinkToHome" => false,
            "title"        => "送信確認",
            "message"      => Message::getMessage(Message::INFO_007),
        ]);
    }
}
