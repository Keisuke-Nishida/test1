@extends('admin.layouts.app')

@section('app_title')
{{ Util::langtext('SIDEBAR_LI_009') }}
@endsection

@section('app_bread')
{{ Util::langtext('SCHEDULE_T_003') . $action }}
@endsection

@php
    $start_hour = isset($data['start_time']) ? Str::substr($data['start_time'], 0, 2) : '00';
    $start_min = isset($data['start_time']) ? Str::substr($data['start_time'], 3, 2) : '00';
    $end_hour = isset($data['end_time']) ? Str::substr($data['end_time'], 0, 2) : '00';
    $end_min = isset($data['end_time']) ? Str::substr($data['end_time'], 3, 2) : '00';
@endphp

@section('app_contents')
<div class="card">
    <div class="card-header">{{ $action }}</div>
    <div class="card-body">
        <form class="form" id="schedule-form" action="{{ route('admin/schedule/save') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="register_mode" value="{{ $register_mode }}" />
            @if (isset($data['id']))
            <input type="hidden" name="id" value="{{ $data['id'] }}" />
            @endif
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('SCHEDULE_L_001') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="name" id="name" tabindex="1" value="{{ isset($data['name']) ? $data['name'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'name'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('SCHEDULE_L_002') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <select class="form-control" name="type" id="type" tabindex="2">
                                <option value=""></option>
                                <option value="1" <?php echo (isset($data['type']) AND $data['type'] == 1) ? " selected" : '' ?>>{{ Util::langtext('SCHEDULE_DD_001') }}</option>
                                <option value="2" <?php echo (isset($data['type']) AND $data['type'] == 2) ? " selected" : '' ?>>{{ Util::langtext('SCHEDULE_DD_002') }}</option>
                                <option value="3" <?php echo (isset($data['type']) AND $data['type'] == 3) ? " selected" : '' ?>>{{ Util::langtext('SCHEDULE_DD_003') }}</option>
                            </select>
                            @include('admin.layouts.components.error_message', ['title' => 'type'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('SCHEDULE_L_003') }}</label>
                        <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SCHEDULE_L_013') }}</div>
                        <div class="col-md-3">
                            <select class="form-control" id="start_time_hour" name="start_time_hour" tabindex="3">
                                @for($i=0; $i<24; $i++)
                                    @php
                                        $hour = $i<10 ? "0".$i : $i
                                    @endphp
                                    <option value="{{ $hour }}"<?php echo ($start_hour == $hour) ? ' selected' : ''; ?>>{{ $hour }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SCHEDULE_L_014') }}</div>
                        <div class="col-md-3">
                            <select class="form-control" id="start_time_min" name="start_time_min" tabindex="4">
                                @for($i=0; $i<60; $i++)
                                    @php
                                        $min = $i<10 ? "0".$i : $i
                                    @endphp
                                    <option value="{{ $min }}"<?php echo ($start_min == $min) ? ' selected' : ''; ?>>{{ $min }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('SCHEDULE_L_004') }}</label>
                        <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SCHEDULE_L_013') }}</div>
                        <div class="col-md-3">
                            <select class="form-control" id="end_time_hour" name="end_time_hour" tabindex="5">
                                @for($i=0; $i<24; $i++)
                                    @php
                                        $hour = $i<10 ? "0".$i : $i
                                    @endphp
                                    <option value="{{ $hour }}"<?php echo ($end_hour == $hour) ? ' selected' : ''; ?>>{{ $hour }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('SCHEDULE_L_014') }}</div>
                        <div class="col-md-3">
                            <select class="form-control" id="end_time_min" name="end_time_min" tabindex="6">
                                @for($i=0; $i<60; $i++)
                                    @php
                                        $min = $i<10 ? "0".$i : $i
                                    @endphp
                                    <option value="{{ $min }}"<?php echo ($end_min == $min) ? ' selected' : ''; ?>>{{ $min }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('SCHEDULE_L_005') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="interval_minutes" id="interval_minutes" tabindex="7" value="{{ isset($data['interval_minutes']) ? $data['interval_minutes'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'interval_minutes'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('SCHEDULE_L_006') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="retry_count" id="retry_count" tabindex="8" value="{{ isset($data['retry_count']) ? $data['retry_count'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'retry_count'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('SCHEDULE_L_007') }}</label>
                        <div class="col-md-9 form-inline">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input class="custom-control-input" type="checkbox" name="cancel_flag" id="cancel_flag" tabindex="9" value="1"<?php echo (isset($data['cancel_flag']) && $data['cancel_flag']) ? ' checked' : ''; ?> />
                                <label class="custom-control-label cursor-pointer mr-3" for="cancel_flag">&nbsp;</label>
                            </div>
                        </div>
                    </div>
                    @if(isset($data['last_run_time']))
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('SCHEDULE_L_008') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="_last_run_time" id="_last_run_time" tabindex="10" value="{{ isset($data['last_run_time']) ? $data['last_run_time'] : '' }}" readonly/>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary width-100" tabindex="11" type="submit">{{ Util::langtext('SCHEDULE_B_006') }}</button>
                    <a class="btn btn-outline-secondary width-100" id="schedule-form-cancel" data-url="/admin/schedule/index" tabindex="11" href="#">{{ Util::langtext('SCHEDULE_B_007') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>
@include('admin.layouts.components.modal.confirm', [
    'title' => Util::langtext('SCHEDULE_L_012'),
    'button_name' => 'OK',
    'message' => \App\Lib\Message::getMessage(\App\Lib\Message::INFO_005, [])
])
@endsection
