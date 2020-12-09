@extends('web.layouts.app')

@section('app_title')
    パスワード変更
@endsection

<!-- Breadcrumb -->
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

<!-- コンテンツ -->
@section('app_contents')
    <hr class="page__hr my-0">
    <!-- row -->
    <div class="article mb-5 mt-3">
        <div class="content col bg-white shadow p-3 content__card--round">
            <form class="form" action="{{ url("/mypage/password/changePassword") }}" method="POST">
                @csrf
                <div class="m-3">
                    <p>下記入力の上、パスワードの変更ボタンを押して変更してください</p>

                    {{-- 現在のパスワード入力フォーム --}}
                    <div class="form-group row">
                        <div class="col-lg-3 text-right">
                            <label for="current_password">現在のパスワード：</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="password" class="form-control" value="" placeholder="現在のパスワード" id="current_password" name="current_password">
                            @include('web.layouts.components.error_message', ['title' => 'current_password'])
                        </div>
                    </div>

                    <hr>

                    {{-- 新しいパスワード入力フォーム --}}
                    <div class="form-group row">
                        <div class="col-lg-3 text-right">
                            <label for="new_password">新しいパスワード：</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="password" class="form-control" value="" placeholder="新しいパスワード" id="new_password" name="new_password">
                            @include('web.layouts.components.error_message', ['title' => 'new_password'])
                            <span style="font-size: 0.8em; color: #bfc4c4">※半角英数8文字以上で入力してください</span>
                        </div>
                    </div>

                    {{-- 確認パスワード入力フォーム --}}
                    <div class="form-group row">
                        <div class="col-lg-3 text-right">
                            <label for="new_password_confirmation">確認パスワード：</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="password" class="form-control" value="" placeholder="確認パスワード" id="new_password_confirmation" name="new_password_confirmation">
                            @include('web.layouts.components.error_message', ['title' => 'new_password_confirmation'])
                        </div>
                    </div>

                    {{-- ボタン --}}
                    <div class="form-group row">
                        <div class="col text-center">
                            <button type="submit" class="btn button__main">パスワードの変更</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
