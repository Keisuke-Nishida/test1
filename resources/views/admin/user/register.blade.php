@extends('admin.layouts.app')

@section('app_title')
{{ Util::langtext('SIDEBAR_LI_002') . ' - ' . $action }}
@endsection

@section('app_bread')
{{ Util::langtext('SIDEBAR_LI_002') }}
@endsection

@section('app_contents')
<div class="card">
    <div class="card-header">{{ $action }}</div>
    <div class="card-body">
        <form class="form" id="user-form" action="{{ route('admin/user/save') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="register_mode" value="{{ $register_mode }}" />
            @if (isset($data['id']))
            <input type="hidden" name="id" value="{{ $data['id'] }}" />
            @endif
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{ Util::langtext('USER_L_017') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="name" id="name" tabindex="1" value="{{ isset($data['name']) ? $data['name'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'name'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('USER_L_021') }}<?php echo ($register_mode == 'create') ? ' <span class="text-danger">&#x203B;</span>' : ''; ?></label>
                        <div class="col-md-9">
                            <input class="form-control" type="password" name="password" id="password" tabindex="3" value="" />
                            @include('admin.layouts.components.error_message', ['title' => 'password'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('USER_L_027') }}<?php echo ($register_mode == 'create') ? ' <span class="text-danger">&#x203B;</span>' : ''; ?></label>
                        <div class="col-md-9">
                            <input class="form-control" type="password" name="password_confirmation" id="confirm-password" tabindex="5" value="" />
                            @include('admin.layouts.components.error_message', ['title' => 'password_confirmation'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('USER_L_023') }}</label>
                        <div class="col-md-9 form-inline">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input class="custom-control-input" type="checkbox" name="system_admin_flag" id="system-admin-flag" tabindex="7" value="1"<?php echo (isset($data['system_admin_flag']) && $data['system_admin_flag']) ? ' checked' : ''; ?> />
                                <label class="custom-control-label cursor-pointer mr-3" for="system-admin-flag">&nbsp;</label>
                            </div>
                            @include('admin.layouts.components.error_message', ['title' => 'system_admin_flag'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('USER_L_024') }}</label>
                        <div class="col-md-9">
                            <select class="form-control" name="status" id="status" tabindex="9">
                                @if (isset($data['status']))
                                <option value="1"<?php echo ($data['status'] == 1) ? ' selected' : ''; ?>>{{ Util::langtext('USER_D_001') }}</option>
                                <option value="2"<?php echo ($data['status'] == 2) ? ' selected' : ''; ?>>{{ Util::langtext('USER_D_002') }}</option>
                                @else
                                <option value="1">{{ Util::langtext('USER_D_001') }}</option>
                                <option value="2">{{ Util::langtext('USER_D_002') }}</option>
                                @endif
                            </select>
                            @include('admin.layouts.components.error_message', ['title' => 'status'])
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('USER_L_019') }}</label>
                        <div class="col-md-9">
                            <select class="form-control" name="customer_id" id="customer-id"tabindex="2"<?php echo ((isset($data['status']) && $data['status'] == 1) || !isset($data['status'])) ? ' disabled' : ''; ?>>
                                <option></option>
                                @foreach ($customers as $customer)
                                @if (isset($data['customer_id']))
                                <option value="{{ $customer['id'] }}"<?php echo ($data['customer_id'] == $customer['id']) ? ' selected' : ''; ?>>{{ $customer['name'] }}</option>
                                @else
                                <option value="{{ $customer['id'] }}">{{ $customer['name'] }}</option>
                                @endif
                                @endforeach
                            </select>
                            @include('admin.layouts.components.error_message', ['title' => 'customer_id'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('USER_L_018') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="login_id" id="login-id" tabindex="4" value="{{ isset($data['login_id']) ? $data['login_id'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'login_id'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('USER_L_020') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="email" id="email" tabindex="6" value="{{ isset($data['login_id']) ? $data['email'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'email'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('USER_L_022') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <select class="form-control" name="role_id" tabindex="8">
                                @foreach ($roles as $role)
                                <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                @endforeach
                            </select>
                            @include('admin.layouts.components.error_message', ['title' => 'role_id'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('USER_L_028') }}</label>
                        <div class="col-md-9">
                            @if (isset($data['login_id']))
                            <input class="form-control" type="text" id="last-login-time" tabindex="10" value="{{ $data['last_login_time'] }}" disabled />
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary width-100" tabindex="11" type="submit">{{ Util::langtext('CUSTOMER_B_007') }}</button>
                    <a class="btn btn-outline-secondary width-100" id="user-form-cancel" data-url="/admin/user/index" tabindex="12" href="#">{{ Util::langtext('CUSTOMER_B_008') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>
@include('admin.layouts.components.modal.confirm', [
    'title' => Util::langtext('SIDEBAR_LI_002'),
    'button_name' => 'OK',
    'message' => \App\Lib\Message::getMessage(\App\Lib\Message::INFO_005, [])
])
@endsection
