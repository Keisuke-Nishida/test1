<?php

namespace App\Http\Controllers\Web;

use App\Lib\Constant;
use App\Lib\Message;
use App\Lib\Util;
use App\Services\Models\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Mypageコントローラー
 * Class MypageController
 * @package App\Http\Controllers\Web
 */
class MypageController extends BaseController
{

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->mainService = $userService;
        $this->mainRoot     = "web/mypage";
    }

    /**
     * 登録項目除外配列(下記以外に不要な項目があればオーバーライド)
     * @return array
     */
    public function except()
    {
        return ["_token", "current_password", "new_password", "new_password_confirmation"];
    }

    /**
     * パスワード変更ページ
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function password()
    {
        return view($this->mainRoot . '/password');
    }

    /**
     * changePassword
     * パスワード変更ボタン押下時の処理
     *
     * @param  mixed $request
     * @return void
     */
    public function changePassword(Request $request)
    {
        return $this->save($request);
    }

    /**
     * バリデーションルール　※オーバーライドして使用する
     * @param Request $request
     * @return array
     */
    public function validation_rules(Request $request)
    {
        return [
            'current_password'          => 'required|password',
            'new_password'              => 'required|min:8|max:255|regex:' . Constant::PASSWORD_REGEX . '|different:current_password|confirmed',
            'new_password_confirmation' => 'required|min:8|max:255|regex:' . Constant::PASSWORD_REGEX,
        ];
    }

    /**
     * バリデーションメッセージ　※オーバーライドして使用する
     * @param Request $request
     * @return array
     */
    public function validation_message(Request $request)
    {
        return [
            'current_password.required' => Message::getMessage(Message::ERROR_001, ["現在のパスワード"]),
            'current_password.password' => Message::getMessage(Message::ERROR_003, ["現在のパスワード"]),

            'new_password.required'  => Message::getMessage(Message::ERROR_001, ["新しいパスワード"]),
            'new_password.min'       => Message::getMessage(Message::ERROR_006, ["新しいパスワード", "6"]),
            'new_password.max'       => Message::getMessage(Message::ERROR_013, ["新しいパスワード", "255"]),
            'new_password.regex'     => Message::getMessage(Message::ERROR_012),
            'new_password.different' => Message::getMessage(Message::ERROR_014, ["新しいパスワード", "現在のパスワード", "パスワード"]),
            'new_password.confirmed' => Message::getMessage(Message::ERROR_004, ["新しいパスワード", "確認パスワード"]),

            'new_password_confirmation.required' => Message::getMessage(Message::ERROR_001, ["確認パスワード"]),
            'new_password_confirmation.min'      => Message::getMessage(Message::ERROR_006, ["確認パスワード", "8"]),
            'new_password_confirmation.max'      => Message::getMessage(Message::ERROR_013, ["確認パスワード", "255"]),
            'new_password_confirmation.regex'    => Message::getMessage(Message::ERROR_012),
        ];
    }

    /**
     * バリデーションエラー時のリダイレクト処理
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function validationFailRedirect(Request $request, $validator)
    {
        return redirect('mypage/password')
            ->withErrors($validator)
            ->withInput();
    }

    /**
     * 保存前処理
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function saveBefore(Request $request)
    {
        $input = parent::saveBefore($request);
        $input['id'] = Auth::user()->id;
        $input['password'] = Util::getHashPassword($request->new_password);
        $input['updated_by'] = Auth::user()->id;

        return $input;
    }

    /**
     * 保存後リダイレクト
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function saveAfterRedirect(Request $request)
    {
        // 対象データの一覧にリダイレクト
        return view("web.layouts.result")->with([
            'isLinkToHome' => true,
            'title'        => "パスワード変更完了",
            'message'      => Message::getMessage(Message::INFO_010),
        ]);
    }

    /**
     * エラー時のリダイレクト先
     * @param \Exception $exception
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function saveErrorRedirect(\Exception $exception)
    {
        return redirect("mypage/password")->with([
            'error_message'   => 'データ登録時にエラーが発生しました。',
            'error_exception' => $exception->getMessage()
        ]);
    }
}
