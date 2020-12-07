@extends('admin.layouts.app')

@section('app_title')
{{ Util::langtext('SIDEBAR_LI_007') }}
@endsection

@section('app_bread')
{{ Util::langtext('SIDEBAR_LI_007') }}
@endsection

@section('app_contents')
<div class="card">
    <div class="card-body">
        @if (session('info_message'))
        <div class="alert alert-success">{{ session('info_message') }}</div>
        @endif
        <div class="data-table-wrapper">
            <div class="detailed-search-wrapper">
                <form class="form" id="shipment-detailed-search-form" action="{{ route('admin/shipment/search') }}" method="post">
                    <div class="clearfix">
                        <div class="float-xl-left float-lg-left float-md-left float-sm-left float-left">{{ Util::langtext('SHIPMENT_L_001') }}</div>
                        <div class="float-xl-right float-lg-right float-md-right float-sm-right float-right">
                            <button class="btn btn-primary" id="search-toggle-button" type="button"><i class="fas fa-plus" id="search-toggle-icon"></i></button>
                        </div>
                    </div>
                    <div class="detailed-search-cont" id="shipment-detailed-search">
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SHIPMENT_L_002') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <div class="clearfix">
                                    <input class="inline-text-input field-border float-left" name="search_date_create_start" id="search-date-create-start" type="text" value="" readonly />
                                    <input class="inline-text-input field-border float-left" name="search_date_create_end" id="search-date-create-end" type="text" value="" readonly />
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SHIPMENT_L_003') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <select class="form-control" name="search_condition" id="search-condition">
                                    <option value=""></option>
                                    @foreach ($conditions as $condition)
                                    <option value="{{ $condition['code'] }}">{{ $condition['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SHIPMENT_L_004') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_customer_code" id="search-customer-code" type="text" value="" />
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SHIPMENT_L_005') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_sale_name" id="search-sale-name" type="text" value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SHIPMENT_L_006') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_destination_name" id="search-destination-name" type="text" value="" />
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SHIPMENT_L_007') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <select class="form-control" name="search_transport_type" id="search-transport-type">
                                    <option value=""></option>
                                    @foreach ($transports as $transport)
                                    <option value="{{ $transport['id'] }}">{{ $transport['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SHIPMENT_L_008') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_order_no" id="search-order-no" type="text" value="" />
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SHIPMENT_L_009') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <div class="clearfix">
                                    <input class="inline-text-input field-border float-left" name="search_order_date_start" id="search-order-date-start" type="text" value="" readonly />
                                    <input class="inline-text-input field-border float-left" name="search_order_date_end" id="search-order-date-end" type="text" value="" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SHIPMENT_L_010') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <div class="clearfix">
                                    <input class="inline-text-input field-border float-left" name="search_shipment_date_start" id="search-shipment-date-start" type="text" value="" readonly />
                                    <input class="inline-text-input field-border float-left" name="search_shipment_date_end" id="search-shipment-date-end" type="text" value="" readonly />
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SHIPMENT_L_011') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <div class="clearfix">
                                    <input class="inline-text-input field-border float-left" name="search_delivery_date_start" id="search-delivery-date-start" type="text" value="" readonly />
                                    <input class="inline-text-input field-border float-left" name="search_delivery_date_end" id="search-delivery-date-end" type="text" value="" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SHIPMENT_L_012') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_shipping_no" id="search-shipping-no" type="text" value="" />
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SHIPMENT_L_013') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <select class="form-control" name="search_voucher_type" id="search-voucher-type">
                                    <option value=""></option>
                                    @foreach ($vouchers as $voucher)
                                    <option value="{{ $voucher['id'] }}">{{ $voucher['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 offset-xl-4 offset-lg-4 offset-md-4 offset-sm-4 offset-4">
                                <button class="btn btn-secondary btn-block" id="shipment-detailed-search-reset" type="button">{{ Util::langtext('SHIPMENT_B_001') }}</button>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <button class="btn btn-primary btn-block" id="shipment-detailed-search-submit" type="button">{{ Util::langtext('SHIPMENT_B_002') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="data-table-cont">
                <table class="table table-striped table-bordered datatable table-sm" id="shipment-table">
                    <thead>
                        <tr>
                            <th>{{ Util::langtext('SHIPMENT_L_014') }}</th>
                            <th>{{ Util::langtext('SHIPMENT_L_015') }}</th>
                            <th>{{ Util::langtext('SHIPMENT_L_016') }}</th>
                            <th>{{ Util::langtext('SHIPMENT_L_017') }}</th>
                            <th>{{ Util::langtext('SHIPMENT_L_018') }}</th>
                            <th>{{ Util::langtext('SHIPMENT_L_019') }}</th>
                            <th>{{ Util::langtext('SHIPMENT_L_020') }}</th>
                            <th>{{ Util::langtext('SHIPMENT_L_021') }}</th>
                            <th>{{ Util::langtext('SHIPMENT_L_022') }}</th>
                            <th>{{ Util::langtext('SHIPMENT_L_023') }}</th>
                            <th>{{ Util::langtext('SHIPMENT_L_024') }}</th>
                            <th>{{ Util::langtext('SHIPMENT_L_025') }}</th>
                            <th>{{ Util::langtext('SHIPMENT_L_026') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@include('admin.layouts.components.modal.message', ['title' => Util::langtext('SIDEBAR_LI_007')])
@include('admin.layouts.components.modal.result_info', ['title' => Util::langtext('SIDEBAR_LI_007')])
@include('admin.layouts.components.modal.result_error', ['title' => Util::langtext('SIDEBAR_LI_007')])
@include('admin.layouts.components.modal.message', ['title' => Util::langtext('SIDEBAR_LI_007')])
@include('admin.layouts.components.modal.confirm', [
    'title'         => Util::langtext('ROLE_MENU_DLG_001'),
    'button_name'   => Util::langtext('ROLE_MENU_L_010'),
    'message'       => \App\Lib\Message::getMessage(\App\Lib\Message::INFO_004, [Util::langtext('SIDEBAR_LI_007')])
])
@endsection
