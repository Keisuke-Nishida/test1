<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Lib\Constant;
use App\Lib\Message;
use App\Lib\Util;
use App\Providers\RouteServiceProvider;
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
    protected $redirectTo = RouteServiceProvider::ADMIN_HOME;

    /**
     * コンストラクタ
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * ログインIDの名称変更
     * @return string
     */
    protected function username()
    {
        return 'login_id';
    }

    /**
     * Guardの認証方法を指定
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard('admin');
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
            $this->username() . '.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('LOGIN_P_001')]),
            'password.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('LOGIN_P_002')]),
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
     * ログイン後のリダイレクト先
     * @return string
     */
    public function redirectPath()
    {
        return $this->redirectTo;
    }

    /**
     * ログイン画面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
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
        Auth::guard('admin')->logout();

        return $this->loggedOut($request);
    }

    /**
     * ログアウト時の遷移先
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function loggedOut(Request $request)
    {
        return redirect(route('admin/login'));
    }

    /**
     * カスタム認証(管理者ユーザーかどうかチェックする)
     * @param Request $request
     * @return bool
     */
    public function attemptLogin(Request $request)
    {
        if ($this->guard()->attempt([
                'login_id'  => $request->input('login_id'),
                'password'  => $request->input('password'),
                'status'    => Constant::STATUS_ADMIN
            ], $request->filled('remember'))) {
            $user = $this->guard()->user();

            session([
                'id' => $user->id,
                'name' => $user->name,
                'login_id' => $user->login_id,
                'email' => $user->email,
                'role_id' => $user->role_id,
                'status' => $user->status,
                'customer_id' => $user->customer_id,
                'system_admin_flag' => $user->system_admin_flag
            ]);

            $user->last_login_time = date('Y-m-d H:i:s');
            $user->save();

            return true;
        }

        return false;
    }

    /**
     * ログインレスポンス
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        // 保存するにチェックしていない場合は、何も行わない
        if (!$request->remember) {
            return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
        }

        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        $cookies = Auth::getCookieJar();
        $value = $cookies->queued(Auth::getRecallerName())->getValue();
        // ログイン保存の時間を変更
        $cookies->queue(Auth::getRecallerName(), $value, Constant::REMEMBER_TOKEN_TIME);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }
}
