@extends('admin.layouts.app')

@section('app_title')
{{ Util::langtext('SIDEBAR_LI_010')  }}
@endsection

@section('app_bread')
{{ Util::langtext('MESSAGE_T_003'). ' - ' . $action }}
@endsection

@section('app_contents')
<div class="card">
    <div class="card-header">{{ $action }}</div>
    <div class="card-body">
        <form class="form" id="message-form" action="{{ route('admin/message/save') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="register_mode" value="{{ $register_mode }}" />
            @if (isset($data['id']))
            <input type="hidden" name="id" value="{{ $data['id'] }}" />
            @endif
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{ Util::langtext('MESSAGE_L_001') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="name" id="name" tabindex="1" value="{{ isset($data['name']) ? $data['name'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'name'])
                        </div>
                    </div>
                    <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{ Util::langtext('MESSAGE_L_002') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="key" id="key" tabindex="2" value="{{ isset($data['key']) ? $data['key'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'key'])
                        </div>
                    </div>
                    <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{ Util::langtext('MESSAGE_L_003') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <textarea class="form-control" type="text" name="value" id="value" tabindex="3" rows="7">{{ isset($data['value']) ? $data['value'] : '' }}</textarea>
                            @include('admin.layouts.components.error_message', ['title' => 'value'])
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary width-100" tabindex="4" type="submit">{{ Util::langtext('MESSAGE_B_007') }}</button>
                    <a class="btn btn-outline-secondary width-100" id="message-form-cancel" data-url="/admin/message/index" tabindex="5" href="#">{{ Util::langtext('MESSAGE_B_008') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>
@include('admin.layouts.components.modal.confirm', [
    'title' => Util::langtext('SIDEBAR_LI_010'),
    'button_name' => 'OK',
    'message' => \App\Lib\Message::getMessage(\App\Lib\Message::INFO_005, [])
])
@endsection
