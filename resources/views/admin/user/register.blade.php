@extends('admin.layouts.app')

@section('app_title')
ユーザー編集
@endsection

@section('app_bread')
ユーザ管理
@endsection

@section('app_contents')
<div class="card">
    <div class="card-header">{{ $action }}</div>
    <div class="card-body">
        <form class="form" id="user-edit-form" action="{{ route('admin/user/save') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="register_mode" value="{{ $register_mode }}" />
            @if (isset($data['id']))
            <input type="hidden" name="id" value="{{ $data['id'] }}" />
            @endif
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">ユーザー名 <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            @if (isset($data['name']))
                            <input class="form-control" type="text" name="name" id="name" tabindex="1" value="{{ $data['name'] }}" />
                            @else
                            <input class="form-control" type="text" name="name" id="name" tabindex="1" value="" />
                            @endif
                            @include('admin.layouts.components.error_message', ['title' => 'name'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">パスワード<?php echo ($register_mode == 'create') ? ' <span class="text-danger">&#x203B;</span>' : ''; ?></label>
                        <div class="col-md-9">
                            <input class="form-control" type="password" name="password" id="password" tabindex="3" value="" />
                            @include('admin.layouts.components.error_message', ['title' => 'password'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">パスワード（確認）<?php echo ($register_mode == 'create') ? ' <span class="text-danger">&#x203B;</span>' : ''; ?></label>
                        <div class="col-md-9">
                            <input class="form-control" type="password" name="password_confirmation" id="confirm-password" tabindex="5" value="" />
                            @include('admin.layouts.components.error_message', ['title' => 'password_confirmation'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">システム管理者フラグ</label>
                        <div class="col-md-9 form-inline">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                @if (isset($data['system_admin_flag']))
                                <input class="custom-control-input" type="checkbox" name="system_admin_flag" id="system-admin-flag" tabindex="7" value="1"<?php echo ($data['system_admin_flag']) ? ' checked' : ''; ?> />
                                @else
                                <input class="custom-control-input" type="checkbox" name="system_admin_flag" id="system-admin-flag" tabindex="7" value="1" />
                                @endif
                                <label class="custom-control-label cursor-pointer mr-3" for="system-admin-flag">&nbsp;</label>
                            </div>
                            @include('admin.layouts.components.error_message', ['title' => 'system_admin_flag'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">ユーザーステータス</label>
                        <div class="col-md-9">
                            <select class="form-control" name="status" id="status" tabindex="9">
                                @if (isset($data['status']))
                                <option value="1"<?php echo ($data['status'] == 1) ? ' selected' : ''; ?>>管理者ユーザー</option>
                                <option value="2"<?php echo ($data['status'] == 2) ? ' selected' : ''; ?>>得意先ユーザー</option>
                                @else
                                <option value="1">管理者ユーザー</option>
                                <option value="2">得意先ユーザー</option>
                                @endif
                            </select>
                            @include('admin.layouts.components.error_message', ['title' => 'status'])
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">得意先ID</label>
                        <div class="col-md-9">
                            @if (isset($data['status']))
                            <select class="form-control" name="customer_id" id="customer-id"tabindex="2"<?php echo ($data['status'] == 1) ? ' disabled' : ''; ?>>
                            @else
                            <select class="form-control" name="customer_id" id="customer-id" tabindex="2" disabled>
                            @endif
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
                        <label class="col-md-3 col-form-label">ログインID <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            @if (isset($data['login_id']))
                            <input class="form-control" type="text" name="login_id" id="login-id" tabindex="4" value="{{ $data['login_id'] }}" />
                            @else
                            <input class="form-control" type="text" name="login_id" id="login-id" tabindex="4" value="" />
                            @endif
                            @include('admin.layouts.components.error_message', ['title' => 'login_id'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">メールアドレス <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            @if (isset($data['login_id']))
                            <input class="form-control" type="text" name="email" id="email" tabindex="6" value="{{ $data['email'] }}" />
                            @else
                            <input class="form-control" type="text" name="email" id="email" tabindex="6" value="" />
                            @endif
                            @include('admin.layouts.components.error_message', ['title' => 'email'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">権限ID <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <select class="form-control" name="role_id" tabindex="8">
                                <option value="1">Sample</option>
                                @foreach ($roles as $role)
                                <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                @endforeach
                            </select>
                            @include('admin.layouts.components.error_message', ['title' => 'role_id'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">最終ログイン日時</label>
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
                    <button class="btn btn-primary width-100" tabindex="11" type="submit">保存</button>
                    <a class="btn btn-outline-secondary width-100" tabindex="12" href="{{ route('admin/user') }}">キャンセル</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
