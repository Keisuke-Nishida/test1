<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * フロント画面用Baseコントローラー
 * Class BaseController
 * @package App\Http\Controllers\Admin
 */
class BaseController extends Controller
{
    // メインサービス
    protected $mainService;
    // メインルート
    protected $mainRoot;

    // ページャーリンク件数
    protected $listPerLink = 5;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 子クラスは、メインサービス・ルートを子クラスで定義する
    }

    /**
     * HOME
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view($this->mainRoot . '/index');
    }

    /**
     * 詳細
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail($id)
    {
        return view($this->mainRoot . '/detail');
    }

    /**
     * バリデーションルール　※オーバーライドして使用する
     * @param Request $request
     * @return array
     */
    public function validation_rules(Request $request)
    {
        return [];
    }

    /**
     * バリデーションメッセージ　※オーバーライドして使用する
     * @param Request $request
     * @return array
     */
    public function validation_message(Request $request)
    {
        return [];
    }

    /**
     * 保存前に、加工したリクエストを追加
     * @param Request $request
     * @return Request
     */
    public function addRequest(Request $request)
    {
        return $request;
    }

    /**
     * バリデーション
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    public function validation(Request $request)
    {
        // リクエスト変数を追加
        $request = $this->addRequest($request);

        // バリデーションルール
        return Validator::make(
            $request->all(),
            $this->validation_rules($request),
            $this->validation_message($request)
        );
    }

    /**
     * バリデーションエラー時のリダイレクト処理
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function validationFailRedirect(Request $request, $validator)
    {
        return redirect($this->mainRoot)
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
        // 除外項目
        $input = $request->except($this->except());
        return $input;
    }

    /**
     * 保存
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function save(Request $request)
    {
        // バリデーション
        $validator = $this->validation($request);

        // バリデーションエラー時はリダイレクト
        if ($validator->fails()) {
            return $this->validationFailRedirect($request, $validator);
        }

        try {
            \DB::beginTransaction();

            // 保存前処理で保存データ作成
            $input = $this->saveBefore($request);
            // 保存処理
            $model = $this->mainService->save($input);
            // 保存後処理
            $this->saveAfter($request, $model);

            \DB::commit();

            // リダイレクト
            return $this->saveAfterRedirect($request);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('database register error:' . $e->getMessage());
            return $this->saveErrorRedirect($e);
        }
    }

    /**
     * 保存後リダイレクト
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function saveAfterRedirect(Request $request)
    {
        // 対象データの一覧にリダイレクト
        if ($this->isCreate($request)) {
            return redirect($this->mainRoot . "/index")->with('create_message', "データを保存しました。");
        } else {
            $query = "?" . preg_replace("/^id=.+?&/", "", $request->getQueryString());
            return redirect($this->mainRoot . "/index" . $query)->with('edit_message', "変更内容を保存しました。");
        }
    }

    /**
     * エラー時のリダイレクト先
     * @param \Exception $exception
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function saveErrorRedirect(\Exception $exception)
    {
        return redirect($this->mainRoot . "/index")->with('error_message', 'データ登録時にエラーが発生しました。');
    }

    /**
     * 保存後処理
     * @param Request $request
     * @param $model
     */
    public function saveAfter(Request $request, Model $model)
    {
        return;
    }
}
