@extends('admin.layouts.app')

@section('app_title')
{{ Util::langtext('SIDEBAR_SUB_LI_001') }}
@endsection

@section('app_bread')
{{ Util::langtext('SIDEBAR_SUB_LI_001') }}
@endsection

@section('app_contents')
<div class="card">
    <div class="card-body">
        @if (session('info_message'))
        <div class="alert alert-success">{{ session('info_message') }}</div>
        @endif
        <div class="data-table-wrapper">
            <div class="detailed-search-wrapper">
                <form class="form" id="customer-destination-detailed-search-form" action="{{ route('admin/customer/destination/search') }}" method="post">
                    <input type="hidden" name="customer_id" id="customer-id" value="{{ $customer_id }}" />
                    <div class="clearfix">
                        <div class="float-xl-left float-lg-left float-md-left float-sm-left float-left">{{ Util::langtext('CUST_DEST_L_001') }}</div>
                        <div class="float-xl-right float-lg-right float-md-right float-sm-right float-right">
                            <button class="btn btn-primary" id="search-toggle-button" type="button"><i class="fas fa-plus" id="search-toggle-icon"></i></button>
                        </div>
                    </div>
                    <div class="detailed-search-cont" id="customer-destination-detailed-search">
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('CUST_DEST_L_002') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_code" id="search-code" type="text" value="" />
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('CUST_DEST_L_003') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_name" id="search-name" type="text" value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('CUST_DEST_L_004') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <select class="form-control" name="search_prefecture" id="search-prefecture">
                                    <option value=""></option>
                                    @foreach ($prefectures as $prefecture)
                                    <option value="{{ $prefecture['id'] }}">{{ $prefecture['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('CUST_DEST_L_005') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_address" id="search-address" type="text" value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('CUST_DEST_L_006') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_tel" id="search-tel" type="text" value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 offset-xl-4 offset-lg-4 offset-md-4 offset-sm-4 offset-4">
                                <button class="btn btn-secondary btn-block" id="customer-destination-detailed-search-reset" type="button">{{ Util::langtext('CUST_DEST_B_001') }}</button>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <button class="btn btn-primary btn-block" id="customer-destination-detailed-search-submit" type="button">{{ Util::langtext('CUST_DEST_B_002') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2"><a class="btn btn-danger btn-icon w-100" data-url="/admin/customer/destination/delete_multiple" id="customer-destination-multiple-delete-button" data-delete-type="multiple" href="#">{{ Util::langtext('CUST_DEST_B_001') }}</a></div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 offset-xl-8 offset-lg-8 offset-md-8 offset-sm-8 offset-8"><a class="btn btn-primary btn-icon w-100" href="{{ route('admin/customer/destination/create', ['customer_id' => $customer_id]) }}">{{ Util::langtext('CUST_DEST_B_002') }}</a></div>
            </div>
            <div class="data-table-cont">
                <table class="table table-striped table-bordered datatable table-sm" id="customer-destination-table">
                    <thead>
                        <tr>
                            <th class="select-checkbox select-all-checkbox"><input id="customer-destination-select-all" type="checkbox" /></th>
                            <th>{{ Util::langtext('CUST_DEST_L_007') }}</th>
                            <th>{{ Util::langtext('CUST_DEST_L_008') }}</th>
                            <th>{{ Util::langtext('CUST_DEST_L_009') }}</th>
                            <th>{{ Util::langtext('CUST_DEST_L_010') }}</th>
                            <th>{{ Util::langtext('CUST_DEST_L_011') }}</th>
                            <th>{{ Util::langtext('CUST_DEST_L_012') }}</th>
                            <th>{{ Util::langtext('CUST_DEST_L_013') }}</th>
                            <th>{{ Util::langtext('CUST_DEST_L_014') }}</th>
                            <th>{{ Util::langtext('CUST_DEST_L_015') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@include('admin.layouts.components.modal.message', ['title' => Util::langtext('SIDEBAR_SUB_LI_001')])
@include('admin.layouts.components.modal.result_info', ['title' => Util::langtext('SIDEBAR_SUB_LI_001')])
@include('admin.layouts.components.modal.result_error', ['title' => Util::langtext('SIDEBAR_SUB_LI_001')])
@include('admin.layouts.components.modal.message', ['title' => Util::langtext('SIDEBAR_SUB_LI_001')])
@include('admin.layouts.components.modal.confirm', [
    'title'         => Util::langtext('CUST_DEST_DLG_001'),
    'button_name'   => Util::langtext('CUSTOMER_L_018'),
    'message'       => \App\Lib\Message::getMessage(\App\Lib\Message::INFO_004, [Util::langtext('SIDEBAR_SUB_LI_001')])
])
@endsection