@extends('admin.layouts.app')

@section('app_title')
{{ Util::langtext('SIDEBAR_LI_003') }}
@endsection

@section('app_bread')
{{ Util::langtext('SIDEBAR_LI_003') }}
@endsection

@section('app_contents')
<div class="card">
    <div class="card-body">
        @if (session('info_message'))
        <div class="alert alert-success">{{ session('info_message') }}</div>
        @endif
        <div class="data-table-wrapper">
            <div class="detailed-search-wrapper">
                <form class="form" id="customer-detailed-search-form" action="{{ route('admin/customer/search') }}" method="post">
                    <div class="clearfix">
                        <div class="float-xl-left float-lg-left float-md-left float-sm-left float-left">{{ Util::langtext('CUSTOMER_L_001') }}</div>
                        <div class="float-xl-right float-lg-right float-md-right float-sm-right float-right">
                            <button class="btn btn-primary" id="search-toggle-button" type="button"><i class="fas fa-plus" id="search-toggle-icon"></i></button>
                        </div>
                    </div>
                    <div class="detailed-search-cont" id="customer-detailed-search">
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('CUSTOMER_L_002') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_customer_code" id="search-customer-code" type="text" value="" />
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('CUSTOMER_L_003') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_customer_name" id="search-customer-name" type="text" value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('CUSTOMER_L_004') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <select class="form-control" name="search_prefecture" id="search-prefecture">
                                    <option value=""></option>
                                    @foreach ($prefectures as $prefecture)
                                    <option value="{{ $prefecture['id'] }}">{{ $prefecture['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('CUSTOMER_L_005') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_customer_address" id="search-customer-address" type="text" value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('CUSTOMER_L_006') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_customer_tel" id="search-customer-tel" type="text" value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 offset-xl-4 offset-lg-4 offset-md-4 offset-sm-4 offset-4">
                                <button class="btn btn-secondary btn-block" id="customer-detailed-search-reset" type="button">{{ Util::langtext('CUSTOMER_B_001') }}</button>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <button class="btn btn-primary btn-block" id="customer-detailed-search-submit" type="button">{{ Util::langtext('CUSTOMER_B_002') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2"><a class="btn btn-danger btn-icon w-100" data-url="/admin/customer/delete_multiple" id="customer-multiple-delete-button" data-delete-type="multiple" href="#">{{ Util::langtext('CUSTOMER_B_003') }}</a></div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 offset-xl-8 offset-lg-8 offset-md-8 offset-sm-8 offset-8"><a class="btn btn-primary btn-icon w-100" href="{{ route('admin/customer/create') }}">{{ Util::langtext('CUSTOMER_B_004') }}</a></div>
            </div>
            <div class="data-table-cont">
                <table class="table table-striped table-bordered datatable table-sm" id="customer-table">
                    <thead>
                        <tr>
                            <th class="select-checkbox select-all-checkbox"><input id="customer-select-all" type="checkbox" /></th>
                            <th>{{ Util::langtext('CUSTOMER_L_002') }}</th>
                            <th>{{ Util::langtext('CUSTOMER_L_003') }}</th>
                            <th>{{ Util::langtext('CUSTOMER_L_009') }}</th>
                            <th>{{ Util::langtext('CUSTOMER_L_004') }}</th>
                            <th>{{ Util::langtext('CUSTOMER_L_011') }}</th>
                            <th>{{ Util::langtext('CUSTOMER_L_012') }}</th>
                            <th>{{ Util::langtext('CUSTOMER_L_006') }}</th>
                            <th>{{ Util::langtext('CUSTOMER_L_014') }}</th>
                            <th>{{ Util::langtext('CUSTOMER_L_015') }}</th>
                            <th>{{ Util::langtext('CUSTOMER_L_016') }}</th>
                            <th>{{ Util::langtext('CUSTOMER_L_017') }}</th>
                            <th>{{ Util::langtext('CUSTOMER_L_018') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@include('admin.layouts.components.modal.message', ['title' => Util::langtext('SIDEBAR_LI_003')])
@include('admin.layouts.components.modal.result_info', ['title' => Util::langtext('SIDEBAR_LI_003')])
@include('admin.layouts.components.modal.result_error', ['title' => Util::langtext('SIDEBAR_LI_003')])
@include('admin.layouts.components.modal.message', ['title' => Util::langtext('SIDEBAR_LI_003')])
@include('admin.layouts.components.modal.confirm', [
    'title'         => Util::langtext('CUSTOMER_DLG_003'),
    'button_name'   => Util::langtext('CUSTOMER_L_018'),
    'message'       => \App\Lib\Message::getMessage(\App\Lib\Message::INFO_004, [Util::langtext('SIDEBAR_LI_003')])
])
@endsection
