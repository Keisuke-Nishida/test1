<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Message;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * 管理画面用Baseコントローラー
 * Class BaseController
 * @package App\Http\Controllers\Admin
 */
class BaseController extends Controller
{
    // メインサービス
    protected $mainService;
    // メインルート
    protected $mainRoot;
    // メインタイトル
    protected $mainTitle;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 子クラスは必ず、サービス・ルート・タイトルを子クラスで定義する
    }

    /**
     * 登録除外配列(基本)
     * @return array
     */
    public function except()
    {
        $base = ['_token', 'register_mode'];
        return array_merge($this->child_except(), $base);
    }

    /**
     * 各機能登録時のRequest除外項目設定　※オーバーライドして使用する
     * @return array
     */
    public function child_except()
    {
        return [];
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
     * index 検索が必要な場合はオーバーライドする
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view($this->mainRoot . '/index');
    }

    /**
     * 一覧画面検索・ソート・リレーション設定　※条件があればオーバーライドする
     * @return array
     */
    public function list_search_condition(Request $request)
    {
        /**
         * 使い方例
         *  condition   => ['name@like' => $request->name, 'id' => $request->id]
         *　　　各テーブルのカラム名指定
         *　　　※検索方法は app\Services\Models\BaseService.php「getConditions」 参考
         *
         *  sort        => [['id' => 'asc'], ['name' => 'desc']]
         *　　　ソートしたいキーとasc,descを指定。先頭から順に優先
         *
         *  relation    => ['user']
         *　　　メインのModelに設定されているリレーションを指定した場合 withで取得する(Eager Loading)
         */

        return [
            'condition' => [],
            'sort'      => [],
            'relation' => [],
        ];
    }

    /**
     * 一覧画面検索処理
     * @return \Illuminate\Http\JsonResponse
     */
    public function list_search(Request $request)
    {
        $search = $this->list_search_condition($request);
        $list = $this->mainService->searchList($search['condition'], $search['sort'], $search['relation']);

        return ['data' => $list];
    }

    /**
     * 詳細
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Request $request)
    {
        return view($this->mainRoot . '/detail', [
            'status' => 1,
            'data'   => $this->mainService->find($request->id),
        ]);
    }

    /**
     * 新規登録画面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view($this->mainRoot . '/register', [
            'register_mode' => 'create',
            'data'          => $this->mainService->model()
        ]);
    }

    /**
     * 編集画面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        return view($this->mainRoot . '/register', [
            'register_mode' => 'edit',
            'data'          => $this->mainService->find($request->id),
        ]);
    }

    /**
     * 新規登録かどうか
     * @param Request $request
     * @return bool
     */
    public function isCreate(Request $request)
    {
        return isset($request->register_mode) && $request->register_mode == 'create';
    }

    /**
     * 編集かどうか
     * @param Request $request
     * @return bool
     */
    public function isEdit(Request $request)
    {
        return isset($request->register_mode) && $request->register_mode == 'edit';
    }

    /**
     * 保存前に加工したリクエストを追加　※必要であればオーバーライドする
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
        return Validator::make($request->all(), $this->validation_rules($request), $this->validation_message($request));
    }

    /**
     * バリデーションエラー時のリダイレクト処理
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function validationFailRedirect(Request $request, $validator)
    {
        return redirect(url()->previous())
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
            DB::beginTransaction();

            // 保存前処理で保存データ作成
            $input = $this->saveBefore($request);
            // 保存処理
            $model = $this->mainService->save($input);
            // 保存後処理
            $this->saveAfter($request, $model);

            DB::commit();

            return $this->saveAfterRedirect($request);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('database register error:' . $e->getMessage());
            throw new \Exception($e);
        }
    }

    /**
     * 保存後処理　※保存後処理が必要な場合オーバーライドする
     * @param Request $request
     * @param $model
     */
    public function saveAfter(Request $request, Model $model) {
        return;
    }

    /**
     * 保存処理後リダイレクト先
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function saveAfterRedirect(Request $request)
    {
        if ($this->isCreate($request)) {
            return redirect($this->mainRoot . '/index')->with('info_message', Message::getMessage(Message::INFO_001, [$this->mainTitle]));
        } else {
            return redirect($this->mainRoot . '/index')->with('info_message', Message::getMessage(Message::INFO_002, [$this->mainTitle]));
        }
    }

    /**
     * 削除
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();

            $this->mainService->delete($request->id);

            DB::commit();

            return ['status' => 1, 'message' => Message::getMessage(Message::INFO_003, [$this->mainTitle])];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('remove error:' . $e->getMessage());
            return ['status' => -1, 'message' => $e->getMessage()];
        }
    }

    /**
     * Deleting multiple rows
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function deleteMultiple(Request $request)
    {
        try {
            DB::beginTransaction();

            $ids = $request->get('id');
            $ids = explode(',', $ids);

            foreach ($ids as $id) {
                $this->mainService->delete($id);
            }

            DB::commit();

            return ['status' => 1, 'message' => Message::getMessage(Message::INFO_003, [$this->mainTitle])];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('remove error:' . $e->getMessage());
            return ['status' => -1, 'message' => $e->getMessage()];
        }
    }
}
