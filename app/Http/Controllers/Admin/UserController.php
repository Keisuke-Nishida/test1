<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Message;
use App\Models\Customer;
use App\Models\Role;
use App\Services\Models\AdminUserService;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
     * Create a new UserController instance
     * 
     * @param AdminUserService $admin_service
     * @return void
     */
    public function __construct(AdminUserService $admin_service)
    {
        parent::__construct();
        $this->mainService = $admin_service;
        $this->mainRoot = 'admin/user';
        $this->mainTitle = 'ユーザ管理';
    }

    /**
     * Method for overriding index method of BaseController class
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.user.index', ['page' => 'user']);
    }

    /**
     * Method for overriding list_search_condition method of BaseController class
     * 
     * @param Request $request
     * @return array
     */
    public function list_search_condition(Request $request)
    {
        $conditions = [];

        if ($request->name) {
            $conditions['name@like'] = $request->name;
        }

        if ($request->login_id) {
            $conditions['login_id@like'] = $request->login_id;
        }
        
        if ($request->status) {
            $conditions['status'] = $request->status;
        }

        if ($request->email) {
            $conditions['email@like'] = $request->email;
        }

        $conditions['deleted_at'] = null;

        return [
            'condition' => $conditions,
            'sort'      => ['id' => 'asc'],
            'relation'  => [],
        ];
    }

    /**
     * Method for overriding create method of BaseController class
     * 
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view($this->mainRoot . '/register', [
            'action' => '登録',
            'register_mode' => 'create',
            'customers' => Customer::select('id', 'name')->whereNull('deleted_at')->get()->toArray(),
            'roles' => Role::select('id', 'name')->whereNull('deleted_at')->get()->toArray(),
            'page' => 'user',
            'data' => [],
        ]);
    }

    /**
     * Method for overriding edit method of BaseController class
     * 
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        return view($this->mainRoot . '/register', [
            'action' => '更新',
            'register_mode' => 'edit',
            'customers' => Customer::select('id', 'name')->whereNull('deleted_at')->get()->toArray(),
            'roles' => Role::select('id', 'name')->whereNull('deleted_at')->get()->toArray(),
            'page' => 'user',
            'data' => $this->mainService->find($request->id),
        ]);
    }

    /**
     * Method for overriding validation_rules method of BaseController class
     * 
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function validation_rules(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:4|max:50',
            'system_admin_flag' => 'integer|min:0|max:1|nullable',
            'status' => 'required|integer|min:1|max:2',
            'customer_id' => 'integer|nullable',
            'login_id' => 'required|string|min:4|max:10',
            'email' => 'required|string|email|min:6|max:255',
            'role_id' => 'required|integer'
        ];

        if ($request->get('register_mode') == 'create') {
            $rules['password'] = 'required|string|min:7|max:15';
            $rules['password_confirmation'] = 'required|string|min:7|max:15|same:password';
        }

        return $rules;        
    }

    /**
     * Method for overriding validation_message method of BaseController class
     * 
     * @param Request $request
     * @return array|void
     */
    public function validation_message(Request $request)
    {
        $messages = [
            'name.required' => Message::getMessage(Message::ERROR_001, ['ユーザー名']),
            'name.min' => Message::getMessage(Message::ERROR_006, ['ユーザー名', '4']),
            'name.max' => Message::getMessage(Message::ERROR_002, ['ユーザー名', '50']),
            'system_admin_flag.integer' => Message::getMessage(Message::ERROR_001, ['システム管理者フラグ']),
            'system_admin_flag.min' => Message::getMessage(Message::ERROR_003, ['システム管理者フラグ']),
            'system_admin_flag.max' => Message::getMessage(Message::ERROR_003, ['システム管理者フラグ']),
            'status.required' => Message::getMessage(Message::ERROR_001, ['ユーザーステータス']),
            'status.integer' => Message::getMessage(Message::ERROR_005, ['ユーザーステータス']),
            'status.min' => Message::getMessage(Message::ERROR_003, ['ユーザーステータス']),
            'status.max' => Message::getMessage(Message::ERROR_003, ['ユーザーステータス']),
            'customer_id.integer' => Message::getMessage(Message::ERROR_001, ['得意先ID']),
            'login_id.required' => Message::getMessage(Message::ERROR_001, ['ログインID']),
            'login_id.min' => Message::getMessage(Message::ERROR_006, ['ログインID', '4']),
            'login_id.max' => Message::getMessage(Message::ERROR_002, ['ログインID', '10']),
            'email.required' => Message::getMessage(Message::ERROR_001, ['メールアドレス']),
            'email.email' => Message::getMessage(Message::ERROR_003, ['メールアドレス']),
            'email.min' => Message::getMessage(Message::ERROR_006, ['メールアドレス', '6']),
            'email.max' => Message::getMessage(Message::ERROR_002, ['メールアドレス', '255']),
            'role_id.required' => Message::getMessage(Message::ERROR_001, ['権限ID']),
            'role_id.integer' => Message::getMessage(Message::ERROR_005, ['権限ID'])
        ];

        if ($request->get('register_mode') == 'create') {
            $messages['password.required'] = Message::getMessage(Message::ERROR_001, ['パスワード']);
            $messages['password.min'] = Message::getMessage(Message::ERROR_006, ['パスワード', '8']);
            $messages['password.max'] = Message::getMessage(Message::ERROR_002, ['パスワード', '255']);
            $messages['password_confirmation.required'] = Message::getMessage(Message::ERROR_001, ['パスワード']);
            $messages['password_confirmation.min'] = Message::getMessage(Message::ERROR_006, ['パスワード', '8']);
            $messages['password_confirmation.max'] = Message::getMessage(Message::ERROR_002, ['パスワード', '255']);
            $messages['password_confirmation.same'] = Message::getMessage(Message::ERROR_002, ['パスワード', 'パスワード（確認）']);
        }

        return $messages;
    }
}
