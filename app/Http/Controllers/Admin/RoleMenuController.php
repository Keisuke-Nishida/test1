<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\Menu;
use App\Services\Models\RoleMenuService;
use App\Models\Role;
use App\Models\RoleMenu;
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
    }

    /**
     * Method for overriding index method of BaseController class
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
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
        $query = $this->mainService->model();
        $query = $query->select(
            'role_menu.role_id',
            'role.name AS role_name',
            'role.type AS role_type',
            'role_menu.menu_id',
            'menu.name AS menu_name'
        )->join('role', 'role_menu.role_id', '=', 'role.id')
        ->join('menu', 'role_menu.menu_id', '=', 'menu.id');

        if ($request->name) {
            $query = $query->where('role.name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->type) {
            $query = $query->where('role.type', $request->type);
        }

        $role_menus = $query->whereNull('role_menu.deleted_at')
            ->whereNull('role.deleted_at')
            ->whereNull('menu.deleted_at')
            ->orderBy('role_menu.id', 'ASC')
            ->get();
        $list = [];

        foreach ($role_menus as $role_menu) {
            $list[$role_menu->role_name]['role_id'] = $role_menu->role_id;
            $list[$role_menu->role_name]['role_name'] = $role_menu->role_name;
            $list[$role_menu->role_name]['role_type'] = $role_menu->role_type;
            $list[$role_menu->role_name]['menu_id'][] = $role_menu->menu_id;
            $list[$role_menu->role_name]['menu_name'][] = $role_menu->menu_name;
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
        try {
            DB::beginTransaction();

            $role_menus = $this->mainService->model();
            $role_menus = $role_menus->where('role_id', $request->role_id)
                ->whereNull('deleted_at')
                ->get();
            $user_id = (isset(Auth::user()->id)) ? Auth::user()->id : 1;
            $now = date('Y-m-d H:i:s');

            foreach ($role_menus as $role_menu) {
                $role_menu->deleted_by = $user_id;
                $role_menu->deleted_at = $now;
                $role_menu->save();
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
        try {
            DB::beginTransaction();

            $role_menus = $this->mainService->model();
            $role_menus = $role_menus->whereIn('role_id', explode(',', $request->role_id))
                ->whereNull('deleted_at')
                ->get();
            $user_id = (isset(Auth::user()->id)) ? Auth::user()->id : 1;
            $now = date('Y-m-d H:i:s');

            foreach ($role_menus as $role_menu) {
                $role_menu->deleted_by = $user_id;
                $role_menu->deleted_at = $now;
                $role_menu->save();
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
