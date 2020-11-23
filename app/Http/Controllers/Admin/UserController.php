<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Message;
use App\Models\Customer;
use App\Models\Role;
use App\Services\Models\AdminUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            'customer_id' => [
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->get('status') == 2 && !$value) {
                        return $fail($attribute . ' is invalid');
                    }
                }
            ],
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
            'name.required' => Message::getMessage(Message::ERROR_001, [langtext('USER_L_002')]),
            'name.min' => Message::getMessage(Message::ERROR_006, [langtext('USER_L_002'), '4']),
            'name.max' => Message::getMessage(Message::ERROR_002, [langtext('USER_L_002'), '50']),
            'system_admin_flag.integer' => Message::getMessage(Message::ERROR_001, [langtext('USER_L_009')]),
            'system_admin_flag.min' => Message::getMessage(Message::ERROR_003, [langtext('USER_L_009')]),
            'system_admin_flag.max' => Message::getMessage(Message::ERROR_003, [langtext('USER_L_009')]),
            'status.required' => Message::getMessage(Message::ERROR_001, [langtext('USER_L_007')]),
            'status.integer' => Message::getMessage(Message::ERROR_005, [langtext('USER_L_007')]),
            'status.min' => Message::getMessage(Message::ERROR_003, [langtext('USER_L_007')]),
            'status.max' => Message::getMessage(Message::ERROR_003, [langtext('USER_L_007')]),
            'customer_id.integer' => Message::getMessage(Message::ERROR_001, [langtext('USER_L_008')]),
            'login_id.required' => Message::getMessage(Message::ERROR_001, [langtext('USER_L_004')]),
            'login_id.min' => Message::getMessage(Message::ERROR_006, [langtext('USER_L_004'), '4']),
            'login_id.max' => Message::getMessage(Message::ERROR_002, [langtext('USER_L_004'), '10']),
            'email.required' => Message::getMessage(Message::ERROR_001, [langtext('USER_L_005')]),
            'email.email' => Message::getMessage(Message::ERROR_003, [langtext('USER_L_005')]),
            'email.min' => Message::getMessage(Message::ERROR_006, [langtext('USER_L_005'), '6']),
            'email.max' => Message::getMessage(Message::ERROR_002, [langtext('USER_L_005'), '255']),
            'role_id.required' => Message::getMessage(Message::ERROR_001, [langtext('USER_L_022')]),
            'role_id.integer' => Message::getMessage(Message::ERROR_005, [langtext('USER_L_022')])
        ];

        if ($request->get('register_mode') == 'create') {
            $messages['password.required'] = Message::getMessage(Message::ERROR_001, [langtext('USER_L_021')]);
            $messages['password.min'] = Message::getMessage(Message::ERROR_006, [langtext('USER_L_021'), '8']);
            $messages['password.max'] = Message::getMessage(Message::ERROR_002, [langtext('USER_L_021'), '255']);
            $messages['password_confirmation.required'] = Message::getMessage(Message::ERROR_001, [langtext('USER_L_021')]);
            $messages['password_confirmation.min'] = Message::getMessage(Message::ERROR_006, [langtext('USER_L_021'), '8']);
            $messages['password_confirmation.max'] = Message::getMessage(Message::ERROR_002, [langtext('USER_L_021'), '255']);
            $messages['password_confirmation.same'] = Message::getMessage(Message::ERROR_002, [langtext('USER_L_021'), langtext(('USER_L_027'))]);
        }

        return $messages;
    }

    /**
     * Method for overriding except method of BaseController class
     *
     * @return array
     */
    public function except()
    {
        $base = ['_token', 'register_mode'];

        if (!request()->get('password')) {
            $base[] = 'password';
            $base[] = 'password_confirmation';
        }

        return array_merge($this->child_except(), $base);
    }

    /**
     * Method for overriding save_before method of BaseController class
     *
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function saveBefore(Request $request)
    {
        $input = $request->except($this->except());

        return $input;
    }

    /**
     * Method for overriding save method of BaseController class
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function save(Request $request)
    {
        $validator = $this->validation($request);

        if ($validator->fails()) {
            return $this->validationFailRedirect($request, $validator);
        }

        try {
            DB::beginTransaction();

            $input = $this->saveBefore($request);

            if (isset($input['password'])) {
                $input['password'] = bcrypt($input['password']);
            }

            if ($input['status'] == 1) {
                $input['customer_id'] = null;
            }

            $model = $this->mainService->save($input);
            $this->saveAfter($request, $model);

            DB::commit();

            return $this->saveAfterRedirect($request);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('database register error:' . $e->getMessage());
            throw new \Exception($e);
        }
    }
}
