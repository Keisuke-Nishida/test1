@extends('web.layouts.app')

@section('app_title')
    パスワード変更
@endsection

@section('app_bread')
    <div class="row mt-2">
        <div class="page__breadcrumb col col-lg-10 d-flex align-items-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item active" aria-current="page">
                        パスワード変更
                    </li>
                </ol>
            </nav>
        </div>
    </div>
@endsection
@section('app_contents')
    <hr class="page__hr my-0">
    <!-- row -->
    <div class="article mb-5 mt-3">
        <div class="content col bg-white shadow p-3 content__card--round">
            <div class="m-3">
                <p>下記入力の上、パスワードの変更ボタンを押して変更してください</p>
                <div class="form-group row">
                    <div class="col-lg-3 text-right">
                        <label for="old_password">現在のパスワード：</label>
                    </div>
                    <div class="col-lg-9">
                        <input type="password" class="form-control" value="" placeholder="現在のパスワード" id="old_password" name="old_password">
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-lg-3 text-right">
                        <label for="new_password">新しいパスワード：</label>
                    </div>
                    <div class="col-lg-9">
                        <input type="password" class="form-control" value="" placeholder="新しいパスワード" id="new_password" name="new_password">
                        <span style="font-size: 0.8em; color: #bfc4c4">※半角英数6文字以上で入力してください</span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-3 text-right">
                        <label for="renew_password">確認パスワード：</label>
                    </div>
                    <div class="col-lg-9">
                        <input type="password" class="form-control" value="" placeholder="確認パスワード" id="renew_password" name="renew_password">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col text-center">
                        <a href="../layouts/result3.blade.html"><button class="btn button__main">パスワードの変更</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
