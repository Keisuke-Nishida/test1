<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Constant;
use App\Lib\Message;
use App\Lib\Util;
use App\Models\Prefecture;
use App\Services\Models\CustomerDestinationService;
use App\Services\Models\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerDestinationController extends BaseController
{
    /**
     * Create a new CustomerDestinationController instance
     * 
     * @param AdminUserService $admin_service
     * @return void
     */
    public function __construct(CustomerDestinationService $customer_destination_service)
    {
        parent::__construct();
        $this->mainService = $customer_destination_service;
        $this->mainRoot = 'admin/customer_destination';
        $this->mainTitle = Util::langtext('SIDEBAR_SUB_LI_001');
        $this->menuKey = Util::getUserRolePrefix('admin') . Constant::MENU_CUSTOMER_DESTINATION;
    }

    /**
     * Method for overriding index method of BaseController class
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexGet($customer_id)
    {
        if (!Util::isAdminUserAllowed($this->menuKey)) {
            return view('admin.errors.403');
        }

        return view('admin.customer_destination.index', [
            'page' => 'customer_destination',
            'customer_id' => $customer_id,
            'prefectures' => Prefecture::select('id', 'name')->whereNull('deleted_at')->get()->toArray()
        ]);
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

        if ($request->code) {
            $conditions['code@like'] = $request->code;
        }

        if ($request->name) {
            $conditions['@multiple'] = [[
                'name' => $request->name,
                'name_kana' => $request->name
            ]];
        }
        
        if ($request->prefecture) {
            $conditions['prefecture_id'] = $request->prefecture;
        }

        if ($request->address) {
            $conditions['@multiple'] = [[
                'address_1' => $request->address,
                'address_2' => $request->address
            ]];
        }

        if ($request->tel) {
            $conditions['tel@like'] = $request->tel;
        }

        $conditions['customer_id'] = $request->customer_id;
        $conditions['deleted_at'] = null;

        return [
            'condition' => $conditions,
            'sort'      => ['id' => 'asc'],
            'relation'  => [],
        ];
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

        $search = $this->list_search_condition($request);
        $list = $this->mainService->searchList($search['condition'], $search['sort'], $search['relation']);
        $data = [];

        if ($list) {
            $list = $list->all();
            $item = [];
            $prefecture_name = '';
    
            foreach ($list as $l) {
                $prefecture_name = (isset($l->prefecture->name)) ? $l->prefecture->name : '';
                $item = $l->toArray();
                $item['prefecture_name'] = $prefecture_name;
                $data[] = $item;
                $prefecture_name = '';
            }
        }
        
        return ['data' => $data];
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

        $customer = new CustomerService();

        return view($this->mainRoot . '/register', [
            'action' => Util::langtext('CUST_DEST_T_002'),
            'register_mode' => 'create',
            'customer_id' => $request->customer_id,
            'customer_name' => $customer->model()->find($request->customer_id)->name,
            'prefectures' => Prefecture::select('id', 'name')->whereNull('deleted_at')->get()->toArray(),
            'page' => 'customer_destination',
            'data' => [],
        ]);
    }

    /**
     * Method for overriding edit method of BaseController class
     * 
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editGet($customer_id, $customer_destination_id)
    {
        if (!Util::isAdminUserAllowed($this->menuKey)) {
            return view('admin.errors.403');
        }

        $customer = new CustomerService();

        return view($this->mainRoot . '/register', [
            'action' => Util::langtext('CUST_DEST_T_003'),
            'register_mode' => 'edit',
            'customer_id' => $customer_id,
            'customer_name' => $customer->model()->find($customer_id)->name,
            'prefectures' => Prefecture::select('id', 'name')->whereNull('deleted_at')->get()->toArray(),
            'page' => 'customer_destination',
            'data' => $this->mainService->find($customer_destination_id),
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
            'customer_id' => 'required|integer',
            'name_kana' => 'nullable|string|min:1|max:255',
            'name' => 'required|string|min:1|max:255',
            'post_no' => 'nullable|string|numeric|digits:7',
            'prefecture' => 'nullable|integer',
            'address_1' => 'nullable|string|min:1|max:255',
            'address_2' => 'nullable|string|min:1|max:255',
            'tel' => 'nullable|string|min:4|max:20',
            'fax' => 'nullable|string|min:4|max:20',
            'kiduke_kanji' => 'nullable|string|min:1|max:255',
        ];

        if ($request->get('register_mode') == 'create') {
            $rules['code'] = 'required|string|numeric|digits_between:1,7|unique:customer_destination,code';
        } elseif ($request->get('register_mode') == 'edit') {
            $customer_destination = $this->mainService->find($request->id);

            $rules['id'] = 'required|integer';
            $rules['code'] = 'required|string|numeric|digits_between:1,7|unique:customer_destination,code,' . $customer_destination->code . ',code';
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
            'code.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('CUST_DEST_L_018')]),
            'code.numeric' => Message::getMessage(Message::ERROR_005, [Util::langtext('CUST_DEST_L_018')]),
            'code.digits_between' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_018'), '1', '7']),
            'code.unique' => Message::getMessage(Message::ERROR_010, [Util::langtext('CUST_DEST_L_018')]),
            'name_kana.min' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_020'), '1', '255']),
            'name_kana.max' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_020'), '1', '255']),
            'name.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('CUST_DEST_L_019')]),
            'name.min' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_019'), '1', '255']),
            'name.max' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_019'), '1', '255']),
            'post_no.numeric' => Message::getMessage(Message::ERROR_005, [Util::langtext('CUST_DEST_L_022')]),
            'post_no.digits' =>  Message::getMessage(Message::ERROR_008, [Util::langtext('CUST_DEST_L_022'), '7']),
            'prefecture_id.integer' => Message::getMessage(Message::ERROR_005, [Util::langtext('CUST_DEST_L_021')]),
            'address_1.min' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_023'), '1', '255']),
            'address_1.max' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_023'), '1', '255']),
            'address_2.min' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_024'), '1', '255']),
            'address_2.max' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_024'), '1', '255']),
            'tel.min' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_025'), '4', '20']),
            'tel.max' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_025'), '4', '20']),
            'fax.min' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_026'), '4', '20']),
            'fax.max' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_026'), '4', '20']),
            'kiduke_kanji.min' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_027'), '1', '255']),
            'kiduke_kanji.max' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_027'), '1', '255']),
        ];

        if ($request->get('register_mode') == 'edit') {
            $messages['id.required'] = Message::getMessage(Message::ERROR_001, [Util::langtext('CUST_DEST_L_028')]);
            $messages['id.integer'] = Message::getMessage(Message::ERROR_005, [Util::langtext('CUST_DEST_L_028')]);
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
            $model = $this->mainService->save($input);
            $this->saveAfter($request, $model);

            DB::commit();

            return $this->saveAfterRedirectParams([
                'customer_id' => $model->customer_id,
                'customer_destination_id' => $model->id,
            ], $request->register_mode);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('database register error:' . $e->getMessage());
            throw new \Exception($e);
        }
    }

    /**
     * 保存処理後リダイレクト先
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function saveAfterRedirectParams($params, $register_mode)
    {
        if ($register_mode == 'create') {
            return redirect()->route('admin/customer/destination', $params)->with('info_message', Message::getMessage(Message::INFO_001, [$this->mainTitle]));
        } else {
            return redirect()->route('admin/customer/destination', $params)->with('info_message', Message::getMessage(Message::INFO_002, [$this->mainTitle]));
        }
    }
}
