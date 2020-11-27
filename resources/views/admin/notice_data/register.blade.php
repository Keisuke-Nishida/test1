@extends('admin.layouts.app')

@section('app_title')
{{ Util::langtext('SIDEBAR_LI_005') . ' - ' . $action }}
@endsection

@section('app_bread')
{{ Util::langtext('SIDEBAR_LI_005') }}
@endsection

@php
    $start_date = isset($data['start_time']) ? date('Y/m/d', strtotime($data['start_time'])) : date('Y/m/d', time());
    $start_hour = isset($data['start_time']) ? date('H', strtotime($data['start_time'])) : date('H', time());
    $start_minute = isset($data['start_time']) ? date('i', strtotime($data['start_time'])) : date('i', time());
    $end_date = isset($data['end_time']) ? date('Y/m/d', strtotime($data['end_time'])) : date('Y/m/d', time());
    $end_hour = isset($data['end_time']) ? date('H', strtotime($data['end_time'])) : date('H', time());
    $end_minute = isset($data['end_time']) ? date('i', strtotime($data['end_time'])) : date('i', time());
@endphp

@section('app_contents')
<div class="card">
    <div class="card-header">{{ $action }}</div>
    <div class="card-body">
        <form class="form" id="notice_data-form" action="{{ route('admin/notice_data/save') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="register_mode" value="{{ $register_mode }}" />
            @if (isset($data['id']))
            <input type="hidden" name="id" value="{{ $data['id'] }}" />
            @endif
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{ Util::langtext('NOTICE_L_001') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="name" id="name" tabindex="1" value="{{ isset($data['name']) ? $data['name'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'name'])
                        </div>
                    </div>
                    <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{ Util::langtext('NOTICE_L_002') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="title" id="title" tabindex="2" value="{{ isset($data['title']) ? $data['title'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'title'])
                        </div>
                    </div>
                    <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{ Util::langtext('NOTICE_L_003') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <textarea class="form-control" type="text" name="body" id="body" tabindex="3" rows="7">{{ isset($data['body']) ? $data['body'] : '' }}</textarea>
                            @include('admin.layouts.components.error_message', ['title' => 'body'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('NOTICE_L_007') }}</label>
                        <div class="col-md-3">
                            <input class="form-control datepicker" type="text" name="notice_data_start_date" id="notice_data_start_date"  placeholder="YYYY/MM/DD" tabindex="4" value="{{ $start_date }}" />
                        </div>
                        <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('NOTICE_L_012') }}</div>
                        <div class="col-md-2">
                            <select class="form-control" name="start" id="notice_data_start_time" tabindex="5">
                                @for($i=0; $i<24; $i++)
                                    @php
                                        $time = $i<10 ? "0".$i : $i
                                    @endphp
                                    <option value="{{ $time }}"<?php echo ($start_hour == $time) ? ' selected' : ''; ?>>{{ $time }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('NOTICE_L_013') }}</div>
                        <div class="col-md-2">
                            <select class="form-control" name="start_minute" id="notice_data_start_minute" tabindex="6">
                                @for($i=0; $i<60; $i++)
                                    @php
                                        $minute = $i<10 ? "0".$i : $i
                                    @endphp
                                    <option value="{{ $minute }}"<?php echo ($start_minute == $minute) ? ' selected' : ''; ?>>{{ $minute }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('NOTICE_L_007') }}</label>
                        <div class="col-md-3">
                            <input class="form-control datepicker" type="text" name="notice_data_end_date" id="notice_data_end_date"  placeholder="YYYY/MM/DD" tabindex="7" value="{{ $end_date }}" />
                        </div>
                        <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('NOTICE_L_012') }}</div>
                        <div class="col-md-2">
                            <select class="form-control" name="end" id="notice_data_end_time" tabindex="8">
                                @for($i=0; $i<24; $i++)
                                    @php
                                        $time = $i<10 ? "0".$i : $i
                                    @endphp
                                    <option value="{{ $time }}"<?php echo ($end_hour == $time) ? ' selected' : ''; ?>>{{ $time }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('NOTICE_L_013') }}</div>
                        <div class="col-md-2">
                            <select class="form-control" name="end_minute" id="notice_data_end_minute" tabindex="9">
                                @for($i=0; $i<60; $i++)
                                    @php
                                        $minute = $i<10 ? "0".$i : $i
                                    @endphp
                                    <option value="{{ $minute }}"<?php echo ($end_minute == $minute) ? ' selected' : ''; ?>>{{ $minute }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary width-100" tabindex="11" type="submit">{{ Util::langtext('CUSTOMER_B_007') }}</button>
                    <a class="btn btn-outline-secondary width-100" id="notice_data-form-cancel" data-url="/admin/notice_data/index" tabindex="12" href="#">{{ Util::langtext('CUSTOMER_B_008') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>
@include('admin.layouts.components.modal.confirm', [
    'title' => Util::langtext('SIDEBAR_LI_005'),
    'button_name' => 'OK',
    'message' => \App\Lib\Message::getMessage(\App\Lib\Message::INFO_005, [])
])
@endsection
