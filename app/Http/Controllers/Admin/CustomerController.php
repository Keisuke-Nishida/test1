<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Constant;
use App\Lib\Message;
use App\Lib\Util;
use App\Models\Prefecture;
use App\Services\Models\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends BaseController
{
    /**
     * Create a new CustomerController instance
     * 
     * @param AdminUserService $admin_service
     * @return void
     */
    public function __construct(CustomerService $customer_service)
    {
        parent::__construct();
        $this->mainService = $customer_service;
        $this->mainRoot = 'admin/customer';
        $this->mainTitle = Util::langtext('SIDEBAR_LI_003');
        $this->menuKey = Util::getUserRolePrefix('admin') . Constant::MENU_CUSTOMER;
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

        return view('admin.customer.index', [
            'page' => 'customer',
            'prefectures' => Prefecture::select('id', 'name')->whereNull('deleted_at')->get()->toArray(),
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

        return view($this->mainRoot . '/register', [
            'action' => Util::langtext('CUSTOMER_T_002'),
            'register_mode' => 'create',
            'prefectures' => Prefecture::select('id', 'name')->whereNull('deleted_at')->get()->toArray(),
            'page' => 'customer',
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
            'action' => Util::langtext('CUSTOMER_T_003'),
            'register_mode' => 'edit',
            'prefectures' => Prefecture::select('id', 'name')->whereNull('deleted_at')->get()->toArray(),
            'page' => 'customer',
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
            'name_kana' => 'nullable|string|min:1|max:255',
            'name' => 'required|string|min:1|max:255',
            'post_no' => 'nullable|string|numeric|digits:7',
            'prefecture' => 'nullable|integer',
            'address_1' => 'nullable|string|min:1|max:255',
            'address_2' => 'nullable|string|min:1|max:255',
            'kiduke_kanji' => 'nullable|string|min:1|max:255',
            'tel' => 'nullable|string|min:4|max:20',
            'fax' => 'nullable|string|min:4|max:20',
            'uriage_1' => 'nullable|integer|digits_between:1,2',
            'uriage_2' => 'nullable|integer|digits_between:1,2',
            'uriage_3' => 'nullable|integer|digits_between:1,2',
            'uriage_4' => 'nullable|integer|digits_between:1,2',
            'uriage_5' => 'nullable|integer|digits_between:1,2',
            'uriage_6' => 'nullable|integer|digits_between:1,2',
            'uriage_7' => 'nullable|integer|digits_between:1,2',
            'uriage_8' => 'nullable|integer|digits_between:1,2'
        ];

        if ($request->get('register_mode') == 'create') {
            $rules['code'] = 'required|string|numeric|digits_between:1,7|unique:customer,code';
        } elseif ($request->get('register_mode') == 'edit') {
            $customer = $this->mainService->find($request->get('id'));

            $rules['id'] = 'required|integer';
            $rules['code'] = 'required|string|numeric|digits_between:1,7|unique:customer,code,' . $customer->code . ',code';
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
            'code.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('CUSTOMER_L_019')]),
            'code.numeric' => Message::getMessage(Message::ERROR_005, [Util::langtext('CUSTOMER_L_019')]),
            'code.digits_between' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_019'), '1', '7']),
            'code.unique' => Message::getMessage(Message::ERROR_010, [Util::langtext('CUSTOMER_L_019')]),
            'name_kana.min' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_020'), '1', '255']),
            'name_kana.max' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_020'), '1', '255']),
            'name.required' => Message::getMessage(Message::ERROR_001, [Util::langtext('CUSTOMER_L_021')]),
            'name.min' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_021'), '1', '255']),
            'name.max' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_021'), '1', '255']),
            'post_no.numeric' => Message::getMessage(Message::ERROR_005, [Util::langtext('CUSTOMER_L_022')]),
            'post_no.digits' =>  Message::getMessage(Message::ERROR_008, [Util::langtext('CUSTOMER_L_022'), '7']),
            'prefecture_id.integer' => Message::getMessage(Message::ERROR_005, [Util::langtext('CUSTOMER_L_023')]),
            'address_1.min' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_024'), '1', '255']),
            'address_1.max' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_024'), '1', '255']),
            'address_2.min' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_025'), '1', '255']),
            'address_2.max' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_025'), '1', '255']),
            'kiduke_kanji.min' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_026'), '1', '255']),
            'kiduke_kanji.max' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_026'), '1', '255']),
            'tel.min' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_027'), '4', '20']),
            'tel.max' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_027'), '4', '20']),
            'fax.min' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_028'), '4', '20']),
            'fax.max' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_028'), '4', '20']),
            'uriage_1.integer' => Message::getMessage(Message::ERROR_005, [Util::langtext('CUSTOMER_L_029')]),
            'uriage_1.digits_between' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_029'), '1', '2']),
            'uriage_2.integer' => Message::getMessage(Message::ERROR_005, [Util::langtext('CUSTOMER_L_030')]),
            'uriage_2.digits_between' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_030'), '1', '2']),
            'uriage_3.integer' => Message::getMessage(Message::ERROR_005, [Util::langtext('CUSTOMER_L_031')]),
            'uriage_3.digits_between' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_031'), '1', '2']),
            'uriage_4.integer' => Message::getMessage(Message::ERROR_005, [Util::langtext('CUSTOMER_L_032')]),
            'uriage_4.digits_between' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_032'), '1', '2']),
            'uriage_5.integer' => Message::getMessage(Message::ERROR_005, [Util::langtext('CUSTOMER_L_033')]),
            'uriage_5.digits_between' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_033'), '1', '2']),
            'uriage_6.integer' => Message::getMessage(Message::ERROR_005, [Util::langtext('CUSTOMER_L_034')]),
            'uriage_6.digits_between' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_034'), '1', '2']),
            'uriage_7.integer' => Message::getMessage(Message::ERROR_005, [Util::langtext('CUSTOMER_L_035')]),
            'uriage_7.digits_between' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_035'), '1', '2']),
            'uriage_8.integer' => Message::getMessage(Message::ERROR_005, [Util::langtext('CUSTOMER_L_036')]),
            'uriage_8.digits_between' => Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_036'), '1', '2']),
        ];

        if ($request->get('register_mode') == 'edit') {
            $messages['id.required'] = Message::getMessage(Message::ERROR_001, [Util::langtext('CUSTOMER_L_038')]);
            $messages['id.integer'] = Message::getMessage(Message::ERROR_005, [Util::langtext('CUSTOMER_L_038')]);
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

        if (!$request->exists('core_system_status')) {
            $request->request->add(['core_system_status' => 0]);
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
