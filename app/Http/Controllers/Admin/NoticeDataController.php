<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Constant;
use App\Lib\Message;
use App\Lib\Util;
use App\Services\Models\NoticeDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NoticeDataController extends BaseController
{
    /**
     * Create a new NoticeDataController instance
     *
     * @param NoticeDataService $admin_service
     * @return void
     */
    public function __construct(NoticeDataService $admin_service)
    {
        parent::__construct();
        $this->mainService = $admin_service;
        $this->mainRoot = 'admin/notice_data';
        $this->mainTitle = Util::langtext('SIDEBAR_LI_005');
        $this->menuKey = Util::getUserRolePrefix('admin') . Constant::MENU_NOTICE;
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

        return view('admin.notice_data.index', ['page' => 'notice_data']);
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

        if ($request->title) {
            $conditions['title@like'] = $request->title;
        }


        if ($request->body) {
            $conditions['body@like'] = $request->body;
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
            'action' => Util::langtext('NOTICE_T_001'),
            'register_mode' => 'create',
            'page' => 'notice_data',
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
            'action' => Util::langtext('NOTICE_T_002'),
            'register_mode' => 'edit',
            'page' => 'notice_data',
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
            'title' => 'required|string|min:4|max:255',
            'body' => 'required',
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
            'name.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('NOTICE_L_001')]),
            'name.min' => Message::getMessage(Message::ERROR_006, [Util::langtext('NOTICE_L_001'), '4']),
            'name.max' => Message::getMessage(Message::ERROR_002, [Util::langtext('NOTICE_L_001'), '50']),
            'title.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('NOTICE_L_002')]),
            'title.min' => Message::getMessage(Message::ERROR_006, [Util::langtext('NOTICE_L_002'), '4']),
            'title.max' => Message::getMessage(Message::ERROR_002, [Util::langtext('NOTICE_L_002'), '255']),
            'body.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('NOTICE_L_003')]),
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

        $start_time = $request->notice_data_start_date . " " . $request->start . ":" . $request->start_minute . ":00";
        $end_time = $request->notice_data_end_date . " " . $request->end . ":" . $request->end_minute . ":00";
        $request->request->add(["start_time" => $start_time]);
        $request->request->add(["end_time" => $end_time]);

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
