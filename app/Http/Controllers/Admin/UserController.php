<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Constant;
use App\Lib\Message;
use App\Lib\Util;
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
        $this->mainTitle = Util::langtext('SIDEBAR_LI_002');
        $this->menuKey = Util::getUserRolePrefix('admin') . Constant::MENU_USER;
    }

    /**
     * Method for overriding index method of BaseController class
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (!Util::isAdminUserAllowed($this->menuKey)) {
            return view('admin.errors.403');
        }

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
        if (!Util::isAdminUserAllowed($this->menuKey)) {
            return view('admin.errors.403');
        }

        return view($this->mainRoot . '/register', [
            'action' => Util::langtext('USER_T_001'),
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
        if (!Util::isAdminUserAllowed($this->menuKey)) {
            return view('admin.errors.403');
        }

        return view($this->mainRoot . '/register', [
            'action' => Util::langtext('USER_T_002'),
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
            'role_id' => 'required|integer'
        ];

        if ($request->get('register_mode') == 'create') {
            $rules['login_id'] = 'required|string|min:4|max:10|unique:user,login_id';
            $rules['email'] = 'required|string|email|min:6|max:255|unique:user,email';
            $rules['password'] = 'required|string|min:8|max:255|confirmed';
        } elseif ($request->get('register_mode') == 'edit') {
            $user = $this->mainService->find($request->get('id'));

            $rules['id'] = 'required|integer';
            $rules['login_id'] = 'required|string|min:4|max:10|unique:user,login_id,' . $user->login_id . ',login_id';
            $rules['email'] = 'required|string|email|min:6|max:255|unique:user,email,' . $user->email . ',email';
            $rules['password'] = 'string|nullable|min:8|max:255|confirmed';
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
            'name.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('USER_L_017')]),
            'name.min' => Message::getMessage(Message::ERROR_006, [Util::langtext('USER_L_017'), '4']),
            'name.max' => Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_017'), '50']),
            'system_admin_flag.integer' => Message::getMessage(Message::ERROR_001, [Util::langtext('USER_L_023')]),
            'system_admin_flag.min' => Message::getMessage(Message::ERROR_003, [Util::langtext('USER_L_023')]),
            'system_admin_flag.max' => Message::getMessage(Message::ERROR_003, [Util::langtext('USER_L_023')]),
            'status.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('USER_L_024')]),
            'status.integer' => Message::getMessage(Message::ERROR_005, [Util::langtext('USER_L_024')]),
            'status.min' => Message::getMessage(Message::ERROR_003, [Util::langtext('USER_L_024')]),
            'status.max' => Message::getMessage(Message::ERROR_003, [Util::langtext('USER_L_024')]),
            'customer_id.integer' => Message::getMessage(Message::ERROR_001, [Util::langtext('USER_L_019')]),
            'login_id.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('USER_L_018')]),
            'login_id.min' => Message::getMessage(Message::ERROR_006, [Util::langtext('USER_L_018'), '4']),
            'login_id.max' => Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_018'), '10']),
            'login_id.unique' => Message::getMessage(Message::ERROR_010, [Util::langtext('USER_L_018')]),
            'email.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('USER_L_020')]),
            'email.email' => Message::getMessage(Message::ERROR_003, [Util::langtext('USER_L_020')]),
            'email.min' => Message::getMessage(Message::ERROR_006, [Util::langtext('USER_L_020'), '6']),
            'email.max' => Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_020'), '255']),
            'email.unique' => Message::getMessage(Message::ERROR_010, [Util::langtext('USER_L_020')]),
            'role_id.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('USER_L_022')]),
            'role_id.integer' => Message::getMessage(Message::ERROR_005, [Util::langtext('USER_L_022')])
        ];

        if ($request->get('register_mode') == 'create') {
            $messages['password.required'] = Message::getMessage(Message::ERROR_001, [Util::langtext('USER_L_021')]);
            $messages['password.min'] = Message::getMessage(Message::ERROR_006, [Util::langtext('USER_L_021'), '8']);
            $messages['password.max'] = Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_021'), '255']);
            $messages['password.confirmed'] = Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_021'), Util::langtext('USER_L_027')]);
            $messages['password_confirmation.required'] = Message::getMessage(Message::ERROR_001, [Util::langtext('USER_L_021')]);
            $messages['password_confirmation.min'] = Message::getMessage(Message::ERROR_006, [Util::langtext('USER_L_021'), '8']);
            $messages['password_confirmation.max'] = Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_021'), '255']);
            $messages['password_confirmation.confirmed'] = Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_021'), Util::langtext('USER_L_027')]);
        } elseif ($request->get('register_mode') == 'edit') {
            $messages['id.required'] = Message::getMessage(Message::ERROR_001, [Util::langtext('USER_L_029')]);
            $messages['id.integer'] = Message::getMessage(Message::ERROR_005, [Util::langtext('USER_L_029')]);
            $messages['password.min'] = Message::getMessage(Message::ERROR_006, [Util::langtext('USER_L_021'), '8']);
            $messages['password.max'] = Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_021'), '255']);
            $messages['password.confirmed'] = Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_021'), Util::langtext('USER_L_027')]);
            $messages['password_confirmation.min'] = Message::getMessage(Message::ERROR_006, [Util::langtext('USER_L_021'), '8']);
            $messages['password_confirmation.max'] = Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_021'), '255']);
            $messages['password_confirmation.confirmed'] = Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_021'), Util::langtext('USER_L_027')]);
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
        if (!Util::isAdminUserAllowed($this->menuKey)) {
            return view('admin.errors.403');
        }

        if (!$request->exists('system_admin_flag')) {
            $request->request->add(['system_admin_flag' => 0]);
        }

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
