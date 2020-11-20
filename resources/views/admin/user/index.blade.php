@extends('admin.layouts.app')

@section('app_title')
ユーザ管理
@endsection

@section('app_bread')
ユーザ管理
@endsection

@section('app_contents')
<div class="card">
    <div class="card-body">
        <div class="data-table-wrapper">
            <div class="detailed-search-wrapper">
                <form class="form" id="user-detailed-search-form" action="{{ route('admin/user/search') }}" method="post">
                    <div class="clearfix">
                        <div class="float-xl-left float-lg-left float-md-left float-sm-left float-left">詳細検索</div>
                        <div class="float-xl-right float-lg-right float-md-right float-sm-right float-right">
                            <button class="btn btn-primary" id="search-toggle-button" type="button"><i class="fas fa-plus" id="search-toggle-icon"></i></button>
                        </div>
                    </div>
                    <div class="detailed-search-cont" id="user-detailed-search">
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">ユーザー名</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_name" id="search-name" type="text" value="" />
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">ログインID</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_login_id" id="search-login-id" type="text" value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">ユーザーステータス</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <select class="form-control" name="search_status" id="search-status">
                                    <option value=""></option>
                                    <option value="1">管理者ユーザー</option>
                                    <option value="2">得意先ユーザー</option>
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 text-right">メールアドレス</div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <input class="form-control" name="search_email" id="search-email" type="text" value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 offset-xl-4 offset-lg-4 offset-md-4 offset-sm-4 offset-4">
                                <button class="btn btn-secondary btn-block" id="user-detailed-search-reset" type="button">クリア</button>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                                <button class="btn btn-primary btn-block" id="user-detailed-search-submit" type="button">検索</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2"><a class="btn btn-danger btn-icon w-100" data-url="/admin/user/delete_multiple" id="user-multiple-delete-button" href="#">一括削除</a></div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 offset-xl-8 offset-lg-8 offset-md-8 offset-sm-8 offset-8"><a class="btn btn-primary btn-icon w-100" href="{{ route('admin/user/create') }}">新期ユーザー登録</a></div>
            </div>
            <div class="data-table-cont">
                <table class="table table-striped table-bordered datatable table-sm" id="user-table">
                    <thead>
                        <tr>
                            <th class="select-checkbox select-all-checkbox"><input id="user-select-all" type="checkbox" /></th>
                            <th>ユーザー名</th>
                            <th>編集</th>
                            <th>ログインID</th>
                            <th>メールアドレス</th>
                            <th>最終ログイン日時</th>
                            <th>ユーザーステータス</th>
                            <th>得意先ID</th>
                            <th>システム管理者フラグ</th>
                            <th>削除</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@include('admin.layouts.components.modal.message', ['title' => 'ユーザ管理'])
@include('admin.layouts.components.modal.result_info', ['title' => 'ユーザ管理'])
@include('admin.layouts.components.modal.result_error', ['title' => 'ユーザ管理'])
@include('admin.layouts.components.modal.message', ['title' => 'ユーザ管理'])
@include('admin.layouts.components.modal.confirm', [
    'title'         => 'ユーザ管理削除確認',
    'button_name'   => '削除',
    'message'       => \App\Lib\Message::getMessage(\App\Lib\Message::INFO_004, ['ユーザ管理'])
])
@endsection
