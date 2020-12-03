@extends('admin.layouts.app')

@section('app_title')
{{ Util::langtext('SIDEBAR_LI_006') . ' - ' . $action }}
@endsection

@section('app_bread')
{{ Util::langtext('BULLETIN_BOARD_T_003') . $action }}
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
        <form class="form" id="bulletin_board-form" action="{{ route('admin/bulletin_board/save') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="register_mode" value="{{ $register_mode }}" />
            @if (isset($data['id']))
            <input type="hidden" name="id" value="{{ $data['id'] }}" />
            @endif
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{ Util::langtext('BULLETIN_BOARD_L_001') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="name" id="name" tabindex="1" value="{{ isset($data['name']) ? $data['name'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'name'])
                        </div>
                    </div>
                    <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{ Util::langtext('BULLETIN_BOARD_L_002') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="title" id="title" tabindex="2" value="{{ isset($data['title']) ? $data['title'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'title'])
                        </div>
                    </div>
                    <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{ Util::langtext('BULLETIN_BOARD_L_003') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <textarea class="form-control" type="text" name="body" id="body" tabindex="3" rows="7">{{ isset($data['body']) ? $data['body'] : '' }}</textarea>
                            @include('admin.layouts.components.error_message', ['title' => 'body'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('BULLETIN_BOARD_L_009') }}</label>
                        <div class="col-md-3">
                            <input class="form-control datepicker" type="text" name="bulletin_board_start_date" id="bulletin_board_start_date"  placeholder="YYYY/MM/DD" tabindex="4" value="{{ $start_date }}" />
                        </div>
                        <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('BULLETIN_BOARD_L_012') }}</div>
                        <div class="col-md-2">
                            <select class="form-control" name="start" id="bulletin_board_start_time" tabindex="5">
                                @for($i=0; $i<24; $i++)
                                    @php
                                        $time = $i<10 ? "0".$i : $i
                                    @endphp
                                    <option value="{{ $time }}"<?php echo ($start_hour == $time) ? ' selected' : ''; ?>>{{ $time }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('BULLETIN_BOARD_L_013') }}</div>
                        <div class="col-md-2">
                            <select class="form-control" name="start_minute" id="bulletin_board_start_minute" tabindex="6">
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
                        <label class="col-md-3 col-form-label">{{ Util::langtext('BULLETIN_BOARD_L_010') }}</label>
                        <div class="col-md-3">
                            <input class="form-control datepicker" type="text" name="bulletin_board_end_date" id="bulletin_board_end_date"  placeholder="YYYY/MM/DD" tabindex="7" value="{{ $end_date }}" />
                        </div>
                        <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('BULLETIN_BOARD_L_012') }}</div>
                        <div class="col-md-2">
                            <select class="form-control" name="end" id="bulletin_board_end_time" tabindex="8">
                                @for($i=0; $i<24; $i++)
                                    @php
                                        $time = $i<10 ? "0".$i : $i
                                    @endphp
                                    <option value="{{ $time }}"<?php echo ($end_hour == $time) ? ' selected' : ''; ?>>{{ $time }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-2 text-right">{{ Util::langtext('BULLETIN_BOARD_L_013') }}</div>
                        <div class="col-md-2">
                            <select class="form-control" name="end_minute" id="bulletin_board_end_minute" tabindex="9">
                                @for($i=0; $i<60; $i++)
                                    @php
                                        $minute = $i<10 ? "0".$i : $i
                                    @endphp
                                    <option value="{{ $minute }}"<?php echo ($end_minute == $minute) ? ' selected' : ''; ?>>{{ $minute }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('BULLETIN_BOARD_L_002') }}</label>
                        <div class="col-md-6">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="bulletin_board_file" id="bulletin_board_file" tabindex="10">
                                <label class="custom-file-label" id="bulletin_board_file_label" for="customFile">{{ isset($data['file_name']) && !empty($data['file_name']) ? $data['file_name'] : "Choose file" }}</label>
                                @include('admin.layouts.components.error_message', ['title' => 'bulletin_board_file'])
                            </div>
                        </div>
                        <div class="col-md-3 col-xl-3 text-right">
                            <input type="hidden" id="bulletin_board_file_hidden" name="bulletin_board_file_hidden" value="{{ isset($data['file_name']) ? $data['file_name'] : "" }}"/>
                            <input type="hidden" id="file_to_be_deleted" name="file_to_be_deleted" value=""/>
                            <button class="btn btn-outline-secondary" id="bulletin_board_delete_file" tabindex="11" type="button">{{ Util::langtext('BULLETIN_BOARD_B_008') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary width-100" tabindex="12" type="submit">{{ Util::langtext('BULLETIN_BOARD_B_006') }}</button>
                    <a class="btn btn-outline-secondary width-100" id="bulletin_board-form-cancel" data-url="/admin/bulletin_board/index" tabindex="12" href="#">{{ Util::langtext('BULLETIN_BOARD_B_007') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>
@include('admin.layouts.components.modal.confirm', [
    'title' => Util::langtext('SIDEBAR_LI_006'),
    'button_name' => 'OK',
    'message' => \App\Lib\Message::getMessage(\App\Lib\Message::INFO_005, [])
])
@endsection
