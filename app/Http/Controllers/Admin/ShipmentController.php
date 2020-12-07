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
            'shipment_data.shipping_no',
            'voucher.name AS voucher_type_name',
        )->join('condition', 'shipment_data.condition_code', '=', 'condition.code')
        ->join('transport', 'shipment_data.transport_type', '=', 'transport.id')
        ->join('voucher', 'shipment_data.voucher_type', '=', 'voucher.id');

        if ($request->date_create_start && $request->date_create_end) {
            $query = $query->whereBetween('shipment_data.data_create_date', [
                $request->date_create_start,
                $request->date_create_end
            ]);
        }

        if ($request->condition) {
            $query = $query->where('shipment_data.condition_code', $request->condition);
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
            $query = $query->where('shipment_data.transport_type', $request->transport_type);
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
            $query = $query->where('shipment_data.shipping_no', 'LIKE', '%' . $request->shipping_no . '%');
        }

        if ($request->voucher_type) {
            $query = $query->where('shipment_data.voucher_type', $request->voucher_type);
        }

        $list = $query->whereNull('shipment_data.deleted_at')
            ->whereNull('condition.deleted_at')
            ->whereNull('transport.deleted_at')
            ->whereNull('voucher.deleted_at')
            ->orderBy('id', 'ASC')->get();

        return ['data' => $list];
    }
}
