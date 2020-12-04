<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\Role;
use App\Services\Models\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageController extends BaseController
{
    /**
     * Create a new UserController instance
     *
     * @param MessageService $admin_service
     * @return void
     */
    public function __construct(MessageService $admin_service)
    {
        parent::__construct();
        $this->mainService = $admin_service;
        $this->mainRoot = 'admin/message';
        $this->mainTitle = Util::langtext('SIDEBAR_LI_010');
    }

    /**
     * Method for overriding index method of BaseController class
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.message.index', ['page' => 'message']);
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
            $conditions['key@like'] = $request->title;
        }


        if ($request->body) {
            $conditions['value@like'] = $request->body;
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
            'action' => Util::langtext('MESSAGE_T_001'),
            'register_mode' => 'create',
            'page' => 'message',
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
            'action' => Util::langtext('MESSAGE_T_002'),
            'register_mode' => 'edit',
            'page' => 'message',
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
            'key' => 'required|string|min:1|max:50',
            'value' => 'required|string|min:1|max:255',
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
            'name.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('MESSAGE_L_001')]),
            'name.min' => Message::getMessage(Message::ERROR_006, [Util::langtext('MESSAGE_L_001'), '1']),
            'name.max' => Message::getMessage(Message::ERROR_002, [Util::langtext('MESSAGE_L_001'), '50']),
            'key.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('MESSAGE_L_002')]),
            'key.min' => Message::getMessage(Message::ERROR_006, [Util::langtext('MESSAGE_L_002'), '1']),
            'key.max' => Message::getMessage(Message::ERROR_002, [Util::langtext('MESSAGE_L_002'), '50']),
            'value.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('MESSAGE_L_003')]),
            'value.min' => Message::getMessage(Message::ERROR_006, [Util::langtext('MESSAGE_L_003'), '1']),
            'value.max' => Message::getMessage(Message::ERROR_002, [Util::langtext('MESSAGE_L_003'), '255']),
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
