<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Constant;
use App\Lib\Util;
use App\Models\Condition;
use App\Models\Transport;
use App\Models\Voucher;
use App\Services\Models\ShipmentDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipmentController extends BaseController
{
    /**
     * Create a new ShipmentController instance
     * 
     * @param RoleMenuService $admin_service
     * @return void
     */
    public function __construct(ShipmentDataService $shipment_service)
    {
        parent::__construct();
        $this->mainService = $shipment_service;
        $this->mainRoot = 'admin/shipment';
        $this->mainTitle = Util::langtext('SIDEBAR_LI_007');
        $this->menuKey = Util::getUserRolePrefix('admin') . Constant::MENU_SHIPMENT;
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

        return view('admin.shipment.index', [
            'conditions' => Condition::select('code', 'name')->whereNull('deleted_at')->get()->toArray(),
            'transports' => Transport::select('id', 'name')->whereNull('deleted_at')->get()->toArray(),
            'vouchers' => Voucher::select('id', 'name')->whereNull('deleted_at')->get()->toArray(),
            'page' => 'shipment',
        ]);
    }

    /**
     * Method for overriding list_search method of BaseController class
     * 
     * @param Request $request
     * @return array
     */
    public function list_search(Request $request)
    {
        if (!Util::isAdminUserAllowed($this->menuKey)) {
            return ['data' => []];
        }

        $query = $this->mainService->model();
        $query = $query->select(
            'shipment_data.id',
            'shipment_data.data_create_date',
            'condition.name AS condition_name',
            'shipment_data.customer_code',
            'shipment_data.sale_name',
            'shipment_data.destination_name',
            'transport.name AS transport_name',
            'shipment_data.order_no',
            'shipment_data.order_date',
            'shipment_data.shipment_date',
            'shipment_data.delivery_date',
            'shipment_detail_data.shipping_no',
            'voucher.name AS voucher_type_name',
        )->join('shipment_detail_data', 'shipment_data.id', '=', 'shipment_detail_data.shipment_data_id')
        ->join('condition', 'shipment_detail_data.condition_code', '=', 'condition.code')
        ->join('transport', 'shipment_detail_data.transport_type', '=', 'transport.id')
        ->join('voucher', 'shipment_data.voucher_type', '=', 'voucher.id');

        if ($request->date_create_start && $request->date_create_end) {
            $query = $query->whereBetween('shipment_data.data_create_date', [
                $request->date_create_start,
                $request->date_create_end
            ]);
        }

        if ($request->condition) {
            $query = $query->where('shipment_detail_data.condition_code', $request->condition);
        }

        if ($request->customer_code) {
            $query = $query->where('shipment_data.customer_code', 'LIKE', '%' . $request->customer_code . '%');
        }

        if ($request->sale_name) {
            $sale_name = $request->sale_name;

            $query = $query->where(function($q) use ($sale_name) {
                $q->where('shipment_data.sale_name', 'LIKE', '%' . $sale_name . '%')
                    ->orWhere('shipment_data.sale_name_kana', 'LIKE', '%' . $sale_name . '%');
            });
        }

        if ($request->destination_name) {
            $destination_name = $request->destination_name;

            $query = $query->where(function($q) use ($destination_name) {
                $q->where('shipment_data.destination_name', 'LIKE', '%' . $destination_name . '%')
                    ->orWhere('shipment_data.destination_name_kana', 'LIKE', '%' . $destination_name . '%');
            });
        }

        if ($request->transport_type) {
            $query = $query->where('shipment_detail_data.transport_type', $request->transport_type);
        }

        if ($request->order_no) {
            $query = $query->where('shipment_data.order_no', 'LIKE', '%' . $request->order_no . '%');
        }

        if ($request->order_date_start && $request->order_date_end) {
            $query = $query->whereBetween('shipment_data.order_date', [
                $request->order_date_start,
                $request->order_date_end
            ]);
        }

        if ($request->shipment_date_start && $request->shipment_date_end) {
            $query = $query->whereBetween('shipment_data.shipment_date', [
                $request->shipment_date_start,
                $request->shipment_date_end
            ]);
        }

        if ($request->delivery_date_start && $request->delivery_date_end) {
            $query = $query->whereBetween('shipment_data.delivery_date', [
                $request->delivery_date_start,
                $request->delivery_date_end
            ]);
        }

        if ($request->shipping_no) {
            $query = $query->where('shipment_detail_data.shipping_no', 'LIKE', '%' . $request->shipping_no . '%');
        }

        if ($request->voucher_type) {
            $query = $query->where('shipment_data.voucher_type', $request->voucher_type);
        }

        $list = $query->whereNull('shipment_data.deleted_at')
            ->whereNull('shipment_detail_data.deleted_at')
            ->whereNull('condition.deleted_at')
            ->whereNull('transport.deleted_at')
            ->whereNull('voucher.deleted_at')
            ->orderBy('id', 'ASC')->get();

        return ['data' => $list];
    }

    /**
     * Method for overriding list_search method of BaseController class
     * 
     * @param int $shipment_id
     * @return array
     */
    private function getShipmentData($shipment_id)
    {
        $shipment_data = $this->mainService->find($shipment_id);
        $shipment_detail_data = $shipment_data->shipment_detail_data;
        return [
            'shipment_id' => $shipment_data->id,
            'data_no' => $shipment_data->data_no,
            'data_type' => $shipment_data->data_type,
            'process_type' => $shipment_data->process_type,
            'condition_code' => $shipment_detail_data->condition->name,
            'data_create_date_time' => date('Y/m/d', strtotime($shipment_data->data_create_date)) . ' ' . $shipment_data->data_create_time,
            'operator_no' => $shipment_data->operator_no,
            'customer_code' => $shipment_data->customer_code,
            'customer_name' => $shipment_data->customer->name,
            'sale_name' => $shipment_data->sale_name,
            'sale_name_kana' => $shipment_data->sale_name_kana,
            'sale_address' => Util::langtext('POSTAL_CODE_SYMBOL') . $shipment_data->sale_post_no . '<br />' . $shipment_data->sale_prefecture->name
                . $shipment_data->sale_address_1 . $shipment_data->sale_address_2,
            'sale_tel' => $shipment_data->sale_tel,
            'destination_code' => $shipment_data->destination_code,
            'destination_name' => $shipment_data->destination_name,
            'destination_name_kana' => $shipment_data->destination_name_kana,
            'destination_address' => Util::langtext('POSTAL_CODE_SYMBOL') . $shipment_data->destination_post_no . '<br />' . $shipment_data->destination_prefecture->name
            . $shipment_data->destination_address_1 . $shipment_data->destination_address_2,
            'destination_tel' => $shipment_data->destination_tel,
            'kiduke_kanji' => $shipment_data->kiduke_kanji,
            'delivery_type' => $shipment_data->delivery_type,
            'voucher_remark_a' => $shipment_data->voucher_remark_a,
            'voucher_remark_b' => $shipment_data->voucher_remark_a,
            'order_no' => $shipment_data->order_no,
            'order_line_no' => $shipment_detail_data->order_line_no,
            'order_date' => date('Y/m/d', strtotime($shipment_data->order_date)),
            'order_confirm_date' => date('Y/m/d', strtotime($shipment_data->order_confirm_date)),
            'shipment_date' => date('Y/m/d', strtotime($shipment_data->shipment_date)),
            'instruction_no' => $shipment_data->instruction_no,
            'jan_code' => $shipment_detail_data->jan_code,
            'item_code' => $shipment_detail_data->item_code,
            'packing_code' => $shipment_detail_data->packing_code,
            'item_name' => $shipment_detail_data->item_name,
            'item_quantity' => $shipment_detail_data->item_quantity,
            'unit_name' => $shipment_detail_data->unit_name,
            'order_price' => $shipment_detail_data->order_price,
            'order_amount' => $shipment_detail_data->order_amount,
            'detail_remarks' => $shipment_detail_data->detail_remarks,
            'delivery_date' => date('Y/m/d', strtotime($shipment_data->delivery_date)),
            'delivery_type' => $shipment_data->delivery_type,
            'delivery_type_name' => $shipment_data->delivery_type,
            'answer_delivery' => $shipment_data->answer_delivery,
            'answer_delivery_date' => date('Y/m/d', strtotime($shipment_data->answer_delivery_date)),
            'answer_delivery_detail' => $shipment_data->answer_delivery_detail,
            'price_type' => $shipment_data->price_type,
            'shipping_no' => $shipment_detail_data->shipping_no,
            'item_lot' => $shipment_detail_data->item_lot,
            'expire_date' => date('Y/m/d', strtotime($shipment_detail_data->expire_date)),
            'price_type' => $shipment_data->price_type,
            'reserve_type' => $shipment_data->reserve_type,
            'voucher_type' =>  $shipment_data->voucher->name,
            'yobi' => $shipment_detail_data->yobi,
        ];
    }

    /**
     * Display printable shipment detail page
     * 
     * @param Request $request
     * @return array
     */
    public function detail(Request $request)
    {
        if (!Util::isAdminUserAllowed($this->menuKey)) {
            return view('admin.errors.403');
        }

        return view($this->mainRoot . '/detail', [
            'action' => Util::langtext('SHIPMENT_T_001'),
            'page' => 'shipment',
            'data' => $this->getShipmentData($request->id)
        ]);
    }

    /**
     * Display detail page of shipment list
     * 
     * @param Request $request
     * @return array
     */
    public function print(Request $request)
    {
        if (!Util::isAdminUserAllowed($this->menuKey)) {
            return view('admin.errors.403');
        }

        return view('admin.print.shipment', ['data' => $this->getShipmentData($request->id)]);
    }
}
