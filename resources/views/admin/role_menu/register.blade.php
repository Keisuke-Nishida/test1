@extends('admin.layouts.app')

@section('app_title')
{{ Util::langtext('SIDEBAR_LI_004') . ' - ' . $action }}
@endsection

@section('app_bread')
{{ Util::langtext('SIDEBAR_LI_004') }}
@endsection

@section('app_contents')
<div class="card">
    <div class="card-header">{{ $action }}</div>
    <div class="card-body">
        <form class="form" id="role-menu-form" action="{{ route('admin/role_menu/save') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="register_mode" id="register-mode" value="{{ $register_mode }}" />
            @if (isset($data['role_id']))
            <input type="hidden" name="role_id" value="{{ $data['role_id'] }}" />
            @endif
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('ROLE_MENU_L_011') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="name" id="name" tabindex="1" value="{{ isset($data['name']) ? $data['name'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'name'])
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('ROLE_MENU_L_012') }}</label>
                        <div class="col-md-9">
                            <select class="form-control" name="type" id="type" tabindex="2">
                                @if (isset($data['type']))
                                <option value="1"<?php echo ($data['type'] == 1) ? ' selected' : ''; ?>>{{ Util::langtext('ROLE_MENU_D_001') }}</option>
                                <option value="2"<?php echo ($data['type'] == 2) ? ' selected' : ''; ?>>{{ Util::langtext('ROLE_MENU_D_002') }}</option>
                                @else
                                <option value="1">{{ Util::langtext('ROLE_MENU_D_001') }}</option>
                                <option value="2">{{ Util::langtext('ROLE_MENU_D_002') }}</option>
                                @endif
                            </select>
                            @include('admin.layouts.components.error_message', ['title' => 'type'])
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 offset-1">
                    <div class="form-group">
                        <p class="text-center"><label class="col-form-label">{{ Util::langtext('ROLE_MENU_L_013') }}</label></p>
                        <select class="form-control multi-select" id="available-menus" tabindex="3" multiple>
                            @if (count($available))
                                @foreach ($available as $a)
                                <option value="{{ $a['id'] }}">{{ $a['name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <div class="mt-5 mb-3 text-center"><button class="btn btn-primary btn-block" id="menu-move-right" tabindex="4" type="button"><i class="fas fa-angle-double-right"></i></button></div>
                        <div class="text-center"><button class="btn btn-primary btn-block" id="menu-move-left" tabindex="5" type="button"><i class="fas fa-angle-double-left"></i></button></div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <p class="text-center"><label class="col-form-label">{{ Util::langtext('ROLE_MENU_L_014') }}</label></p>
                        <select class="form-control multi-select" name="selected_menus[]" id="selected-menus" tabindex="6" multiple>
                            @if (isset($data['selected_menus']))
                                @foreach ($data['selected_menus'] as $selected_menu)
                                <option value="{{ $selected_menu['id'] }}" selected>{{ $selected_menu['name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                        @include('admin.layouts.components.error_message', ['title' => 'selected_menus'])
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary width-100" tabindex="7" type="submit">{{ Util::langtext('ROLE_MENU_B_005') }}</button>
                    <a class="btn btn-outline-secondary width-100" id="role-menu-form-cancel" data-url="/admin/role_menu/index" tabindex="8" href="#">{{ Util::langtext('ROLE_MENU_B_006') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>
@include('admin.layouts.components.modal.confirm', [
    'title' => Util::langtext('SIDEBAR_LI_004'),
    'button_name' => 'OK',
    'message' => \App\Lib\Message::getMessage(\App\Lib\Message::INFO_005, [])
])
<input type="hidden" id="menu-data" value="{{ $menu_data }}" />
@if (isset($data['selected_menus']))
<input type="hidden" id="selected-type" value="{{ $data['type'] }}" />
<input type="hidden" id="selected-menus-data" value="{{ json_encode($data['selected_menus']) }}" />
@endif
@endsection
