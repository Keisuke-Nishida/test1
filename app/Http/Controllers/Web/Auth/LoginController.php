<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Lib\Constant;
use App\Lib\Message;
use App\Providers\RouteServiceProvider;
use App\Services\Models\CustomerService;
use App\Services\Models\UserAgreeDataService;
use App\Services\Models\UserService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $userService;
    protected $userAgreeDataService;
    protected $customerService;

    /**
     * コンストラクタ
     * LoginController constructor.
     */
    public function __construct(
        UserService $userService,
        UserAgreeDataService $userAgreeDataService,
        CustomerService $customerService
        )
    {
        $this->middleware('guest:web')->except('logout');
        $this->userService = $userService;
        $this->userAgreeDataService = $userAgreeDataService;
        $this->customerService = $customerService;
    }

    /**
     * ログインIDの名称変更
     * @return string
     */
    protected function username() {
        return "login_id";
    }

    /**
     * Guardの認証方法を指定
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard('web');
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
            'password' => 'required|string',
        ], [
            $this->username().".required"   => Message::getMessage(Message::ERROR_001, ["ログインID"]),
            "password.required"          => Message::getMessage(Message::ERROR_001, ["パスワード"]),
        ]);
    }

    /**
     * 認証エラー時メッセージ
     * @param Request $request
     */
    protected function sendFailedLoginResponse(Request $request) {
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
     * ログイン後のリダイレクト先
     * @return string
     */
    public function redirectPath() {
        return $this->redirectTo;
    }
    /**
     * ログイン画面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('web.auth.login');
    }

    /**
     * 認証処理時の最終ログイン日時更新
     * @param Request $request
     * @param $user
     */
    protected function authenticated(Request $request, $user)
    {
        $user->last_login_time = now();
        $user->save();
    }

    /**
     * ログアウト処理
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        return $this->loggedOut($request);
    }

    /**
     * ログアウト時の遷移先
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function loggedOut(Request $request)
    {
        return redirect(route('login'));
    }

    /**
     * カスタム認証(得意先ユーザーかどうかチェックする)
     * @param Request $request
     * @return bool
     */
    public function attemptLogin(Request $request) {
        if ($this->guard()->attempt(
            [
                'login_id'  => $request->input('login_id'),
                'password'  => $request->input('password'),
                'status'    => Constant::STATUS_CUSTOMER
            ], $request->filled('remember'))
        ) {
            return true;
        }
        return false;
    }

    /**
     * ログインレスポンス
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLoginResponse(Request $request) {
        // 得意先マスタの基幹システム連携ステータスの更新
        $this->customerService->updateCoreSystemStatus($this->guard()->user());
        // 保存するにチェックしていない場合は、何も行わない
        if (!$request->remember) {
            return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
        }
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        $cookies = $this->guard()->getCookieJar();
        $value = $cookies->queued($this->guard()->getRecallerName())->getValue();
        // ログイン保存の時間を変更
        $cookies->queue($this->guard()->getRecallerName(), $value, Constant::REMEMBER_TOKEN_TIME);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    /**
     * 初回ログイン時に送信されるメールのリンクからの認証
     *
     * @param  mixed $request
     * @return void
     */
    public function loginFromEmail($reset_token)
    {
        $user = $this->userService->getUserDataFromResetToken($reset_token);

        // ユーザー情報の取得OK
        if ($user) {
            // リセットトークンの有効期限内
            if ($this->userService->isEarlierThanLimitTime($user)) {
                // ログイン
                Auth::login($user);
                // ログイン状態を保存する場合
                // Auth::login($user, true);

                // ログインできた場合
                if (Auth::check()) {
                    \DB::beginTransaction();
                    try {
                        // ログイン時ユーザーマスタの更新
                        $this->userService->updateUserDataAtLoginFromEmail($user);
                        // リセットトークンとその有効期限日時の初期化
                        $this->userService->initResetToken($user);
                        // 同意履歴情報の登録
                        $this->userAgreeDataService->saveUserAgreeData($user->id);
                        // 得意先連携更新
                        $this->customerService->updateCoreSystemStatus($user);
                        \DB::commit();
                    } catch (\Exception $e) {
                        \DB::rollBack();
                        \Log::error('database save error:' . $e->getMessage());
                        throw new \Exception($e);
                    }

                    // 利用確認完了ページへ遷移
                    return view('web.layouts.result')->with([
                        "isLinkToHome" => true,
                        "title"        => "メッセージ",
                        "message"      => Message::getMessage(Message::INFO_008),
                    ]);
                }
            }
        }

        // 利用確認失敗ページへ遷移
        return view('web.layouts.result')->with([
            "isLinkToHome"  => false,
            "isLinkToLogin" => true,
            "title"         => "メッセージ",
            "message"       => Message::getMessage(Message::INFO_011),
        ]);
    }
}
