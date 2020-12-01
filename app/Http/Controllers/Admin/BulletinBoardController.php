<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Message;
use App\Lib\Util;
use App\Services\Models\BulletinBoardDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BulletinBoardController extends BaseController
{
    /**
     * Create a new UserController instance
     *
     * @param BulletinBoardDataService $admin_service
     * @return void
     */
    public function __construct(BulletinBoardDataService $admin_service)
    {
        parent::__construct();
        $this->mainService = $admin_service;
        $this->mainRoot = 'admin/bulletin_board';
        $this->mainTitle = Util::langtext('SIDEBAR_LI_006');
    }

    /**
     * Method for overriding index method of BaseController class
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.bulletin_board.index', ['page' => 'bulletin_board_data']);
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
        return view($this->mainRoot . '/register', [
            'action' => Util::langtext('BULLETIN_BOARD_T_001'),
            'register_mode' => 'create',
            'page' => 'bulletin_board_data',
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
            'action' => Util::langtext('BULLETIN_BOARD_T_002'),
            'register_mode' => 'edit',
            'page' => 'bulletin_board_data',
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
            'title' => 'required|string|min:1|max:50',
            'body' => 'required|string|min:1|max:255',
            'bulletin_board_file' => 'mimes:gif,jpeg,jpg,png,pfd,docx|max:2048',
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
            'name.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('BULLETIN_BOARD_L_001')]),
            'name.min' => Message::getMessage(Message::ERROR_006, [Util::langtext('BULLETIN_BOARD_L_001'), '1']),
            'name.max' => Message::getMessage(Message::ERROR_002, [Util::langtext('BULLETIN_BOARD_L_001'), '50']),
            'title.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('BULLETIN_BOARD_L_002')]),
            'title.min' => Message::getMessage(Message::ERROR_006, [Util::langtext('BULLETIN_BOARD_L_002'), '1']),
            'title.max' => Message::getMessage(Message::ERROR_002, [Util::langtext('BULLETIN_BOARD_L_002'), '50']),
            'body.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('BULLETIN_BOARD_L_003')]),
            'body.min' => Message::getMessage(Message::ERROR_006, [Util::langtext('BULLETIN_BOARD_L_003'), '1']),
            'body.max' => Message::getMessage(Message::ERROR_002, [Util::langtext('BULLETIN_BOARD_L_003'), '255']),
            'bulletin_board_file.mimes' => Message::getMessage(Message::ERROR_011, [Util::langtext('BULLETIN_BOARD_L_014')]),
            'bulletin_board_file.max' => Message::getMessage(Message::ERROR_012, ['2']),
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
        $validator = $this->validation($request);

        if ($validator->fails()) {
            return $this->validationFailRedirect($request, $validator);
        }

        try {

            $start_time = $request->bulletin_board_start_date . " " . $request->start . ":" . $request->start_minute . ":00";
            $end_time = $request->bulletin_board_end_date . " " . $request->end . ":" . $request->end_minute . ":00";
            $request->request->add(["start_time" => $start_time]);
            $request->request->add(["end_time" => $end_time]);

            if(!empty($request->file_to_be_deleted))
            {
                // delete previous file
                Storage::disk('local')->delete('bulletin_board/' . $request->file_to_be_deleted);
            }

            if(!empty($request->file('bulletin_board_file')))
            {
                // rename file
                $file_name = time() . "-" . $request->file('bulletin_board_file')->getClientOriginalName();
                // upload file
                $request->file('bulletin_board_file')->storeAs('bulletin_board', $file_name, 'local');
                // set db file_name
                $request->request->add(['file_name' => $file_name]);
            }
            else
            {
                if(!empty($request->bulletin_board_file_hidden))
                {
                    // Applicable on edit mode and not touching the file. Just edit bulletin info text
                    $request->request->add(['file_name' => $request->bulletin_board_file_hidden]);
                }
                else
                {
                    // Applicable on reg mode without uploading file
                    // also applicable on edit mode and deleting current file
                    $request->request->add(['file_name' => '']);
                }
            }

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
