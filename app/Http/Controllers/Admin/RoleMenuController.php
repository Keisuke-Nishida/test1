<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Constant;
use App\Lib\Message;
use App\Lib\Util;
use App\Models\Menu;
use App\Services\Models\RoleMenuService;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleMenuController extends BaseController
{
    /**
     * Create a new RoleMenuController instance
     * 
     * @param RoleMenuService $admin_service
     * @return void
     */
    public function __construct(RoleMenuService $role_menu_service)
    {
        parent::__construct();
        $this->mainService = $role_menu_service;
        $this->mainRoot = 'admin/role_menu';
        $this->mainTitle = Util::langtext('SIDEBAR_LI_004');
        $this->menuKey = Util::getUserRolePrefix('admin') . Constant::MENU_ROLE;
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

        return view('admin.role_menu.index', [
            'roles' => Role::select('id', 'name')->get()->toArray(),
            'menus' => Menu::select('id', 'name')->get()->toArray(),
            'page' => 'role_menu',
        ]);
    }

    /**
     * Method for overriding list_search method of BaseController class
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function list_search(Request $request)
    {
        if (!Util::isAdminUserAllowed($this->menuKey)) {
            return ['data' => []];
        }

        $query = $this->mainService->model();
        $query = $query->select(
            'role.id AS role_id',
            'role.name AS role_name',
            'role.type AS role_type',
            'role_menu.menu_id',
            'menu.name AS menu_name'
        )->rightJoin('role', 'role_menu.role_id', '=', 'role.id')
        ->leftJoin('menu', 'role_menu.menu_id', '=', 'menu.id');

        if ($request->name) {
            $query = $query->where('role.name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->type) {
            $query = $query->where('role.type', $request->type);
        }

        $role_menus = $query->whereNull('role_menu.deleted_at')
            ->whereNull('role.deleted_at')
            ->whereNull('menu.deleted_at')
            ->orderBy('role.id', 'ASC')
            ->get();
        $list = [];

        foreach ($role_menus as $role_menu) {
            $list[$role_menu->role_name]['role_id'] = $role_menu->role_id;
            $list[$role_menu->role_name]['role_name'] = $role_menu->role_name;
            $list[$role_menu->role_name]['role_type'] = $role_menu->role_type;
            $list[$role_menu->role_name]['menu_id'][] = $role_menu->menu_id;
            $list[$role_menu->role_name]['menu_name'][] = ($role_menu->menu_name) ? $role_menu->menu_name : '';
        }

        $list = array_map(function($values) {
            return [
                'role_id' => $values['role_id'],
                'role_name' => $values['role_name'],
                'role_type' => $values['role_type'],
                'menu_ids' => implode(',', $values['menu_id']),
                'menu_names' => implode(', ', $values['menu_name'])
            ];
        }, $list);

        if ($request->menu_id) {
            foreach ($list as $key => $value) {
                if (!preg_match("/\b" . $request->menu_id . "\b/i", $value['menu_ids'])) {
                    unset($list[$key]);
                }
            }
        }

        return ['data' => array_values($list)];
    }

    /**
     * Method for overriding delete method of BaseController class
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        if (!Util::isAdminUserAllowed($this->menuKey)) {
            return ['status' => -1, 'message' => 'Unauthorized'];
        }

        try {
            DB::beginTransaction();

            $role_menus = $this->mainService->model();
            $role_menus = $role_menus->where('role_id', $request->role_id)
                ->whereNull('deleted_at')
                ->get();
            $user_id = (isset(Auth::user()->id)) ? Auth::user()->id : 1;
            $now = date('Y-m-d H:i:s');

            if ($role_menus->count()) {
                foreach ($role_menus as $role_menu) {
                    $role_menu->deleted_by = $user_id;
                    $role_menu->deleted_at = $now;
                    $role_menu->save();
                }
            }

            $role = Role::where('id', $request->role_id)->first();

            if ($role) {
                $role->deleted_by = $user_id;
                $role->deleted_at = $now;
                $role->save();
            }

            DB::commit();

            return ['status' => 1, 'message' => Message::getMessage(Message::INFO_003, [$this->mainTitle])];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('remove error:' . $e->getMessage());
            return ['status' => -1, 'message' => $e->getMessage()];
        }
    }

    /**
     * Method for overriding deleteMultiple method of BaseController class
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function deleteMultiple(Request $request)
    {
        if (!Util::isAdminUserAllowed($this->menuKey)) {
            return ['status' => -1, 'message' => 'Unauthorized'];
        }

        try {
            DB::beginTransaction();

            $role_menus = $this->mainService->model();
            $role_menus = $role_menus->whereIn('role_id', explode(',', $request->role_id))
                ->whereNull('deleted_at')
                ->get();
            $user_id = (isset(Auth::user()->id)) ? Auth::user()->id : 1;
            $now = date('Y-m-d H:i:s');

            if ($role_menus->count()) {
                foreach ($role_menus as $role_menu) {
                    $role_menu->deleted_by = $user_id;
                    $role_menu->deleted_at = $now;
                    $role_menu->save();
                }
            }

            $role = Role::where('id', $request->role_id)->first();

            if ($role) {
                $role->deleted_by = $user_id;
                $role->deleted_at = $now;
                $role->save();
            }

            DB::commit();

            return ['status' => 1, 'message' => Message::getMessage(Message::INFO_003, [$this->mainTitle])];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('remove error:' . $e->getMessage());
            return ['status' => -1, 'message' => $e->getMessage()];
        }
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

        $menus = Menu::select('id', 'name', 'type')
            ->orderBy('id', 'ASC')
            ->get()
            ->toArray();
        $menu_data = [];

        foreach ($menus as $menu) {
            $menu_data[$menu['type']][] = [
                'id' => $menu['id'],
                'name' => $menu['name']
            ];
        }

        $available = $menu_data[1];
        $menu_data = json_encode($menu_data);

        return view($this->mainRoot . '/register', [
            'action' => Util::langtext('ROLE_MENU_T_002'),
            'register_mode' => 'create',
            'menu_data' => $menu_data,
            'available' => $available,
            'page' => 'role_menu',
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

        $menus = Menu::select('id', 'name', 'type')
            ->orderBy('id', 'ASC')
            ->get()
            ->toArray();
        $menu_data = [];

        foreach ($menus as $menu) {
            $menu_data[$menu['type']][] = [
                'id' => $menu['id'],
                'name' => $menu['name']
            ];
        }

        $role_menus = $this->mainService->model()->where('role_id', $request->role_id)->get();
        $selected_type = $role_menus[0]->role->type;
        $selected_menu_data = [];

        foreach ($role_menus as $role_menu) {
            $selected_menu_data[$role_menu->role->type][] = [
                'id' => $role_menu->menu->id,
                'name' => $role_menu->menu->name
            ];
        }

        $trimmed_menu_data = array_udiff($menu_data[$selected_type], $selected_menu_data[$selected_type], function($a, $b) {
            return $a['id'] - $b['id'];
        });
        $menu_data[$selected_type] = $trimmed_menu_data;
        $menu_data = json_encode($menu_data);

        return view($this->mainRoot . '/register', [
            'action' => Util::langtext('ROLE_MENU_T_003'),
            'register_mode' => 'edit',
            'menu_data' => $menu_data,
            'available' => $trimmed_menu_data,
            'page' => 'role_menu',
            'data' => [
                'role_id' => $request->role_id,
                'name' => $role_menus[0]->role->name,
                'type' => $role_menus[0]->role->type,
                'selected_menus' => $selected_menu_data[$selected_type]
            ],
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
            'type' => 'required|integer|min:1|max:2',
            'selected_menus' => 'array',
            'selected_menus.*' => 'integer',
        ];

        if ($request->get('register_mode') == 'create') {
            $rules['name'] = 'required|string|min:1|max:50|unique:role,name';
        } elseif ($request->get('register_mode') == 'edit') {
            $role = Role::where('id', $request->role_id)->first();

            $rules['role_id'] = 'required|integer';
            $rules['name'] = 'required|string|min:1|max:50|unique:role,name,' . $role->name . ',name';
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
            'name.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('ROLE_MENU_L_011')]),
            'name.min' => Message::getMessage(Message::ERROR_006, [Util::langtext('ROLE_MENU_L_011'), '1']),
            'name.max' => Message::getMessage(Message::ERROR_006, [Util::langtext('ROLE_MENU_L_011'), '50']),
            'type.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('ROLE_MENU_L_012')]),
            'type.integer' => Message::getMessage(Message::ERROR_005, [Util::langtext('ROLE_MENU_L_012')]),
            'type.min' => Message::getMessage(Message::ERROR_003, [Util::langtext('ROLE_MENU_L_012')]),
            'type.max' => Message::getMessage(Message::ERROR_003, [Util::langtext('ROLE_MENU_L_012')]),
            'selected_menus.array' => Message::getMessage(Message::ERROR_003, [Util::langtext('ROLE_MENU_L_014')]),
            'selected_menus.*' => Message::getMessage(Message::ERROR_005, [Util::langtext('ROLE_MENU_L_014')]),
        ];

        if ($request->get('register_mode') == 'edit') {
            $messages['role_id.required'] = Message::getMessage(Message::ERROR_001, [Util::langtext('ROLE_MENU_L_015')]);
            $messages['role_id.integer'] = Message::getMessage(Message::ERROR_005, [Util::langtext('ROLE_MENU_L_015')]);
        }

        return $messages;
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

        $validator = $this->validation($request);

        if ($validator->fails()) {
            return $this->validationFailRedirect($request, $validator);
        }

        try {
            DB::beginTransaction();

            $input = $this->saveBefore($request);
            $role = new Role();
            $now = date('Y-m-d H:i:s');
            $user_id = (isset(Auth::user()->id)) ? Auth::user()->id : 1;

            if ($request->register_mode == 'edit') {
                $role = $role->where('id', $request->role_id)->first();
            } elseif ($request->register_mode == 'create') {
                $role->created_at = $now;
                $role->created_by = $user_id;
            }

            $role->name = $input['name'];
            $role->type = $input['type'];
            $role->updated_at = $now;
            $role->updated_by = $user_id;
            $role->save();

            if ($request->register_mode == 'edit') {
                $role_menus = $this->mainService->model()->where('role_id', $request->role_id)->get();

                if ($role_menus->count()) {
                    foreach ($role_menus as $role_menu) {
                        $role_menu->deleted_by = $user_id;
                        $role_menu->deleted_at = $now;
                        $role_menu->save();
                    }
                }
            }

            foreach ($request->selected_menus as $selected_menu) {
                $model = $this->mainService->save([
                    'role_id' => $role->id,
                    'menu_id' => $selected_menu
                ]);
                $this->saveAfter($request, $model);
            }

            DB::commit();

            return $this->saveAfterRedirect($request);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('database register error:' . $e->getMessage());
            throw new \Exception($e);
        }
    }
}
