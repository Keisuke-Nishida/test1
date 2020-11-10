<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/web/login.css">
    <link rel="stylesheet" href="/css/web/style.css">

    <!-- fontawesome -->
    <!-- cdn廃止予定のためdownload版を使用 -->
    <link rel="stylesheet" href="/fontawesome-free-5.14.0-web/css/all.css">

    <title>ログイン | {{ env('APP_NAME') }}</title>

</head>

<body class="page">

<form class="form-login  bg-white p-5 shadow content__card--round " action="/login" method="post">
    {{ csrf_field() }}
    <div class="text-center">
        <h1>Web情報閲覧サービス</h1>
    </div>

    <!-- ロゴ1 -->
    <div class="text-center mt-3">
        <img src="/images/LOGO.png" alt="">
    </div>

    <div class="mt-4">
        <!-- ログインIDフォーム -->
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="fa fa-user fa-fw" aria-hidden="true"></i>
                </div>
            </div>
            <input type="text" id="login_id" name="login_id" class="form-control" placeholder="ログインID" value="{{ old('login_id') }}">
        </div>
        @include('web.layouts.components.error_message', ['title' => 'login_id'])

        <!-- パスワードフォーム -->
        <div class="input-group mt-2">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="fa fa-key fa-fw" aria-hidden="true"></i>
                </div>
            </div>
            <input type="password" id="password" name="password" class="form-control" placeholder="パスワード" value="{{ old('password') }}">
        </div>
        @include('web.layouts.components.error_message', ['title' => 'password'])
    </div>

    <!-- ログイン状態保存チェックボックス -->
    <div class="custom-control custom-checkbox mt-2 cursor-pointer">
        <input type="checkbox" class="custom-control-input" id="remember" name="remember">
        <label class="custom-control-label" for="remember">ログイン状態を保存</label>
    </div>

    <!-- ログインボタン -->
    <button type="submit" class="button__login btn btn-primary btn-block mt-4">ログイン</button>

    <div class="mt-2 text-center">
        <a class="link__main" href="/reset_password">パスワードを忘れた方はこちら</a>
    </div>

</form>

<!-- ロゴ2 -->
<footer class="text-center mt-3">
    <div>
        <img src="/images/login_takiilogo.png" alt="">
    </div>
    <p class="mt-2 text-center">Copyright © TAKII & CO.,LTD.</p>
</footer>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>

@include('web.layouts.components.modal.agreement', ["agreement" => "規約"])

<script>
    $(function () {
        $('#agree_login').on('click', function () {
            $('#modalAgree').modal('show');
        });
    });
</script>
</body>

</html>
