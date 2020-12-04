@extends('admin.layouts.app')

@section('app_title')
{{ Util::langtext('MESSAGE_L_010') }}
@endsection

@section('app_bread')
{{ Util::langtext('MESSAGE_L_010') }}
@endsection

@section('app_contents')
<div class="card">
    <div class="card-body">
        @if (session('info_message'))
        <div class="alert alert-success">{{ session('info_message') }}</div>
        @endif
        <div class="data-table-wrapper">
            <div class="detailed-search-wrapper">
                <form class="form" id="message-detailed-search-form" action="{{ route('admin/message/search') }}" method="post">
                    <div class="clearfix">
                    <div class="float-xl-left float-lg-left float-md-left float-sm-left float-left">{{ Util::langtext('MESSAGE_L_006') }}</div>
                        <div class="float-xl-right float-lg-right float-md-right float-sm-right float-right">
                            <button class="btn btn-primary" id="search-toggle-button" type="button"><i class="fas fa-plus" id="search-toggle-icon"></i></button>
                        </div>
                    </div>
                    <div class="detailed-search-cont" id="message-detailed-search">
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('MESSAGE_L_001') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_name" id="search-name" type="text" value="" tabindex="1"/>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('MESSAGE_L_002') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_key" id="search-key" type="text" value="" tabindex="2"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('MESSAGE_L_003') }}</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_value" id="search-value" type="text" value="" tabindex="3"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 offset-xl-4 offset-lg-4 offset-md-4 offset-sm-4 offset-4">
                                <button class="btn btn-secondary btn-block" id="message-detailed-search-reset" type="button" tabindex="4">{{ Util::langtext('MESSAGE_B_003') }}</button>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <button class="btn btn-primary btn-block" id="message-detailed-search-submit" type="button" tabindex="5">{{ Util::langtext('MESSAGE_B_004') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2"><a class="btn btn-danger btn-icon w-100" data-url="/admin/message/delete_multiple" id="message-multiple-delete-button" href="#">{{ Util::langtext('MESSAGE_B_001') }}</a></div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 offset-xl-8 offset-lg-8 offset-md-8 offset-sm-8 offset-8"><a class="btn btn-primary btn-icon w-100" href="{{ route('admin/message/create') }}">{{ Util::langtext('MESSAGE_B_002') }}</a></div>
            </div>
            <div class="data-table-cont">
                <table class="table table-striped table-bordered datatable table-sm" id="message-table">
                    <thead>
                        <tr>
                            <th class="select-checkbox select-all-checkbox"><input id="message-select-all" type="checkbox" /></th>
                            <th>{{ Util::langtext('MESSAGE_L_001') }}</th>
                            <th>{{ Util::langtext('MESSAGE_L_004') }}</th>
                            <th>{{ Util::langtext('MESSAGE_L_002') }}</th>
                            <th>{{ Util::langtext('MESSAGE_L_003') }}</th>
                            <th>{{ Util::langtext('MESSAGE_L_005') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@include('admin.layouts.components.modal.message', ['title' => Util::langtext('SIDEBAR_LI_010')])
@include('admin.layouts.components.modal.result_info', ['title' => Util::langtext('SIDEBAR_LI_010')])
@include('admin.layouts.components.modal.result_error', ['title' => Util::langtext('SIDEBAR_LI_010')])
@include('admin.layouts.components.modal.message', ['title' => Util::langtext('SIDEBAR_LI_010')])
@include('admin.layouts.components.modal.confirm', [
    'title'         => Util::langtext('MESSAGE_DLG_001'),
    'button_name'   => Util::langtext('MESSAGE_B_009'),
    'message'       => \App\Lib\Message::getMessage(\App\Lib\Message::INFO_004, [Util::langtext('SIDEBAR_LI_010')])
])
@endsection
