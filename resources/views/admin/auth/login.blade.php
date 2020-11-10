@extends('admin.layouts.no_login')

@section('app_title')
    ログイン
@endsection

@section('app_contents')
    <div class="col-md-6">
        <div class="card-group">
            <div class="card p-4 login-body">
                <div class="card-body">
                    <form action="/admin/login" method="post">
                        {{ csrf_field() }}
                        <h2 class="text-center">Web情報閲覧サービス管理</h2>
                        <p class="text-muted">サインイン</p>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                            </div>
                            <input class="form-control" type="text" name="login_id" placeholder="ログインID">
                        </div>
                        @include('admin.layouts.components.error_message', ['title' => 'login_id'])
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                            </div>
                            <input class="form-control" type="password" name="password" placeholder="パスワード">
                        </div>
                        @include('admin.layouts.components.error_message', ['title' => 'password'])
                        <div class="input-group mb-4">
                            <div class="custom-control custom-checkbox cursor-pointer">
                                <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                                <label class="custom-control-label cursor-pointer" for="remember">ログイン状態を保存</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary px-4" type="button" id="btn_login">ログイン</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
