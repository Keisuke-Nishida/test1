<?php

namespace App\Http\Controllers\Admin;

use App\Models\InvoiceData;
use App\Models\ShipmentData;

/**
 * HOMEコントローラー
 * Class HomeController
 * @package App\Http\Controllers\Page
 */
class HomeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService = null;
        $this->mainRoot = 'admin/home';
    }

    public function index()
    {
        $invoice_count = InvoiceData::whereNull('deleted_at')->get()->count();
        $shipment_count = ShipmentData::whereNull('deleted_at')->get()->count();

        $data = [
            'page' => 'home',
            'invoice_count' => '',
            'shipment_count' => '',
        ];

        return view('admin.home.index', $data);
    }
}
