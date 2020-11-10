<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- CSS -->
    <!-- bootstrap4用 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <!-- dataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.7/css/fixedHeader.bootstrap4.min.css" />
    <!-- datetimepicker -->
    <link rel="stylesheet" href="/css/web/datetimepicker/jquery.datetimepicker.min.css">
    <!-- 共通 -->
    <link rel="stylesheet" href="/css/web/style.css" />

    <!-- fontawesome -->
    <!-- cdn廃止予定のためdownload版を使用 -->
    <link rel="stylesheet" href="/fontawesome-free-5.14.0-web/css/all.css" />

    <title>@yield('app_title') | {{ env('APP_NAME') }}</title>

    @yield('app_css')

</head>

<body class="page">
    <!-- header -->
    <header class="header sticky-top">
        <div class="header__bachground-image">
            <div class="header__bachground-image__mask">
                <div class="d-flex flex-row align-items-center px-5 py-3 shadow">
                    <!-- ロゴ -->
                    <div class="mr-auto">
                        <a class="header__logo my-0 mr-lg-auto" href="/">
                            <img src="/images/logo.png" alt="LOGO画像">
                        </a>
                    </div>
                    @auth
                    <!-- ログイン時のメニュー -->
                    <div class="d-flex flex-column flex-lg-row ml-5">
                        <div class="header__text d-flex align-items-center">
                            ログイン中：{{ Auth::user()->customer->name }}(ID:{{ Auth::user()->login_id }}) 様
                        </div>
                        <div class="d-flex justify-content-end mt-2 mt-lg-0 ml-0 ml-lg-3">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button"
                                        class="btn button__main dropdown-toggle btn-sm px-3" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    会員メニュー
                                </button>
                                <div class="header__dropdown-menu dropdown-menu dropdown-menu-right"
                                     aria-labelledby="btnGroupDrop1">
                                    <a class="dropdown-item header__dropdown-menu__text header__dropdown-menu__item"
                                       href="/mypage/password"><i class="fa fa-key" aria-hidden="true"></i>
                                        パスワード変更</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item header__dropdown-menu__text header__dropdown-menu__item"
                                       href="/logout"><i class="fa fa-lock" aria-hidden="true"></i>
                                        ログアウト</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    <!-- header -->

    <!-- container -->
    <div class="container">
        @yield('app_bread')

        <hr class="page__hr my-0">

        @yield('app_contents')
    </div>
    <!-- container -->

    <!-- footer -->
    <footer class="footer">
        <div class="contariner">
            <div class="row m-0">
                <div class="col text-center p-2">
                    <small class="footer__text text-white">Copyright © TAKII & CO.,LTD.</small>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer -->

    <!-- JavaScript -->
    <!-- jQuery本体 -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"
            integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>
    <!-- dataTables -->
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.7/js/dataTables.fixedHeader.min.js"></script>
    <!-- datetimepicker -->
    <script src="/js/web/datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <!-- 共有 -->
    <script src="/js/web/main.js"></script>

    @yield('app_js')

</body>
