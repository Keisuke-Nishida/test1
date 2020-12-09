@extends('admin.layouts.app')

@section('app_title')
{{ Util::langtext('SIDEBAR_LI_007') }}
@endsection

@section('app_bread')
{{ Util::langtext('SIDEBAR_LI_007') . ' - ' . $action }}
@endsection

@section('app_contents')
<div class="card">
    <div class="card-header">{{ $action }}</div>
    <div class="card-body">
        <input type="hidden" id="shipment-id" value="{{ $data['shipment_id'] }}" />
        <table class="table table-responsive mb-4 bg-transparent detail-table">
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_027') }}</th>
                <td>{{ $data['data_no'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_028') }}</th>
                <td>{{ $data['data_type'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_029') }}</th>
                <td>{{ $data['process_type'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_030') }}</th>
                <td>{{ $data['condition_code'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_031') }}</th>
                <td>{{ $data['data_create_date_time'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_032') }}</th>
                <td>{{ $data['operator_no'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_033') }}</th>
                <td>{{ $data['customer_code'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_034') }}</th>
                <td>{{ $data['customer_name'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_035') }}</th>
                <td>{{ $data['sale_name'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_036') }}</th>
                <td>{{ $data['sale_name_kana'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_037') }}</th>
                <td>{!! $data['sale_address'] !!}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_038') }}</th>
                <td>{{ $data['sale_tel'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_039') }}</th>
                <td>{{ $data['destination_code'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_040') }}</th>
                <td>{{ $data['destination_name'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_041') }}</th>
                <td>{{ $data['destination_name_kana'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_042') }}</th>
                <td>{!! $data['destination_address'] !!}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_043') }}</th>
                <td>{{ $data['destination_tel'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_044') }}</th>
                <td>{{ $data['kiduke_kanji'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_045') }}</th>
                <td>{{ $data['delivery_type'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_046') }}</th>
                <td>{{ $data['voucher_remark_a'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_047') }}</th>
                <td>{{ $data['voucher_remark_b'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_048') }}</th>
                <td>{{ $data['order_no'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_049') }}</th>
                <td>{{ $data['order_line_no'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_050') }}</th>
                <td>{{ $data['order_date'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_051') }}</th>
                <td>{{ $data['order_confirm_date'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_052') }}</th>
                <td>{{ $data['shipment_date'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_053') }}</th>
                <td>{{ $data['instruction_no'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_054') }}</th>
                <td>{{ $data['jan_code'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_055') }}</th>
                <td>{{ $data['item_code'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_056') }}</th>
                <td>{{ $data['packing_code'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_057') }}</th>
                <td>{{ $data['item_name'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_058') }}</th>
                <td>{{ $data['item_quantity'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_059') }}</th>
                <td>{{ $data['unit_name'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_060') }}</th>
                <td>{{ $data['order_price'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_061') }}</th>
                <td>{{ $data['order_amount'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_062') }}</th>
                <td>{{ $data['detail_remarks'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_063') }}</th>
                <td>{{ $data['delivery_date'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_064') }}</th>
                <td>{{ $data['delivery_type'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_065') }}</th>
                <td>{{ $data['delivery_type_name'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_066') }}</th>
                <td>{{ $data['answer_delivery'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_067') }}</th>
                <td>{{ $data['answer_delivery_date'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_068') }}</th>
                <td>{{ $data['answer_delivery_detail'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_069') }}</th>
                <td>{{ $data['price_type'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_070') }}</th>
                <td>{{ $data['shipping_no'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_071') }}</th>
                <td>{{ $data['item_lot'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_072') }}</th>
                <td>{{ $data['expire_date'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_073') }}</th>
                <td>{{ $data['price_type'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_074') }}</th>
                <td>{{ $data['reserve_type'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_075') }}</th>
                <td>{{ $data['voucher_type'] }}</td>
            </tr>
            <tr class="d-flex">
                <th>{{ Util::langtext('SHIPMENT_L_076') }}</th>
                <td>{{ $data['yobi'] }}</td>
            </tr>
        </table>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary width-100" id="shipment-detail-print" tabindex="1" type="button">{{ Util::langtext('SHIPMENT_B_003') }}</button>
                <a class="btn btn-outline-secondary width-100" id="shipment-detail-cancel" data-url="/admin/shipment/index" tabindex="2" href="#">{{ Util::langtext('SHIPMENT_B_004') }}</a>
            </div>
        </div>
    </div>
</div>
@include('admin.layouts.components.modal.confirm', [
    'title' => Util::langtext('SIDEBAR_LI_002'),
    'button_name' => 'OK',
    'message' => \App\Lib\Message::getMessage(\App\Lib\Message::INFO_005, [])
])
@endsection