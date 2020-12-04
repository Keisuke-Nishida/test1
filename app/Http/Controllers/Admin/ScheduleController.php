<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Constant;
use App\Lib\Message;
use App\Lib\Util;
use App\Services\Models\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleController extends BaseController
{
    /**
     * Create a new UserController instance
     *
     * @param ScheduleController $admin_service
     * @return void
     */
    public function __construct(ScheduleService $admin_service)
    {
        parent::__construct();
        $this->mainService = $admin_service;
        $this->mainRoot = 'admin/schedule';
        $this->mainTitle = Util::langtext('SIDEBAR_LI_009');
        $this->menuKey = Util::getUserRolePrefix('admin') . Constant::MENU_SCHEDULE;
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

        return view('admin.schedule.index', ['page' => 'schedule']);
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

        if ($request->type) {
            $conditions['type'] = $request->type;
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
            'action' => Util::langtext('SCHEDULE_T_001'),
            'register_mode' => 'create',
            'page' => 'schedule',
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
            'action' => Util::langtext('SCHEDULE_T_002'),
            'register_mode' => 'edit',
            'page' => 'schedule',
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
            'name' => 'required|string|min:1|max:50',
            'type' => 'required',
            'interval_minutes' => 'required|integer|min:1|max:1440',
            'retry_count' => 'required|integer|min:1|max:999'
        ];

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
            'name.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('SCHEDULE_L_001')]),
            'name.min' => Message::getMessage(Message::ERROR_006, [Util::langtext('SCHEDULE_L_001'), '1']),
            'name.max' => Message::getMessage(Message::ERROR_002, [Util::langtext('SCHEDULE_L_001'), '50']),
            'type.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('SCHEDULE_L_002')]),
            'interval_minutes.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('SCHEDULE_L_005')]),
            'interval_minutes.min' => Message::getMessage(Message::ERROR_013, ['1', '1440']),
            'interval_minutes.max' => Message::getMessage(Message::ERROR_013, ['1', '1440']),
            'retry_count.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('SCHEDULE_L_006')]),
            'retry_count.min' => Message::getMessage(Message::ERROR_013, ['1', '999']),
            'retry_count.max' => Message::getMessage(Message::ERROR_013, ['1', '999'])
        ];

        return $messages;
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

        $start_time = $request->start_time_hour . ":" . $request->start_time_min;
        $end_time = $request->end_time_hour . ":" . $request->end_time_min;
        $request->request->add(["start_time" => $start_time]);
        $request->request->add(["end_time" => $end_time]);

        if(!$request->exists('cancel_flag')){
            $request->request->add(['cancel_flag'=>0]);
        }

        $validator = $this->validation($request);

        if ($validator->fails()) {
            return $this->validationFailRedirect($request, $validator);
        }

        try {
            DB::beginTransaction();

            $input = $this->saveBefore($request);

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
