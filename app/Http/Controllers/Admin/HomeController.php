<?php

namespace App\Http\Controllers\Admin;

use App\Lib\Constant;
use App\Lib\Util;
use App\Models\InvoiceData;
use App\Models\ShipmentData;

/**
 * HOMEコントローラー
 * Class HomeController
 * @package App\Http\Controllers\Page
 */
class HomeController extends BaseController
{
    /**
     * Create a new HomeController instance
     *
     * @param BulletinBoardDataService $admin_service
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->mainService = null;
        $this->mainRoot = 'admin/home';
        $this->menuKey = Util::getUserRolePrefix('admin') . Constant::MENU_HOME;
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

        $invoice_count = InvoiceData::whereNull('deleted_at')->get()->count();
        $shipment_count = ShipmentData::whereNull('deleted_at')->get()->count();

        $data = [
            'page' => 'home',
            'invoice_count' => $invoice_count,
            'shipment_count' => $shipment_count,
        ];

        return view('admin.home.index', $data);
    }
}
