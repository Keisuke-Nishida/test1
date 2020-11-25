<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Util;
use App\Models\Prefecture;
use App\Services\Models\CustomerDestinationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerDestinationController extends BaseController
{
    public function __construct(CustomerDestinationService $customer_destination_service)
    {
        parent::__construct();
        $this->mainService = $customer_destination_service;
        $this->mainRoot = 'admin/customer_destination';
        $this->mainTitle = Util::langtext('SIDEBAR_SUB_LI_001');
    }

    /**
     * Method for overriding index method of BaseController class
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexGet($customer_id)
    {
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
        
        return ["data" => $data];
    }
}