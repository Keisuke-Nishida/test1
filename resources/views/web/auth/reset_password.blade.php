@extends('web.layouts.app')

@section('app_title')
    パスワード再発行
@endsection

@section('app_bread')
    <div class="row mt-2">
        <!-- パンくずリスト -->
        <div class="page__breadcrumb col col-lg-10 d-flex align-items-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item active" aria-current="page">
                        パスワード再発行
                    </li>
                </ol>
            </nav>
        </div>
    </div>
@endsection
@section('app_contents')
    <hr class="page__hr my-0">
    <!-- row -->
    <div class="article mb-5">
        <div class="content col bg-white shadow p-3 content__card--round">
            <div class="m-3">
                <p>
                    ご登録頂いているメールアドレスを入力の上、送信ボタンを押してください<br>
                    メールアドレス宛に再発行手続きURLが記載されたメールを送信いたします
                </p>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <input type="email" class="form-control" value="" placeholder="ご登録メールアドレス" id="email"
                               name="email">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col text-center">
                        <a href=""><button class="btn button__main">メール送信</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
