<!DOCTYPE html>
<html lang="ja">
<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <link rel="stylesheet" href="/css/admin/coreui.min.css">
    <link rel="stylesheet" href="/css/admin/fontawesome/all.css">
    <link rel="stylesheet" href="/css/admin/style.css">
    <link rel="stylesheet" href="/css/admin/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/css/admin/datetimepicker/jquery.datetimepicker.min.css">

    <title>@yield('app_title') | {{ env('APP_NAME') }}</title>
    @yield('app_css')
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
<header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="system-title">{{ env('APP_NAME') }}</div>

    @auth
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown mr-3">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user"></i> {{ Auth::user()->name }} 様
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="/admin/logout"><i class="fa fa-lock"></i> ログアウト</a>
            </div>
        </li>
    </ul>
    @endauth
</header>

<div class="app-body">
    @auth
        <div class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin">
                            <i class="fas fa-home fa-lg"></i> HOME
                        </a>
                    </li>
                    <li class="nav-item nav-dropdown ">
                        <a class="nav-link nav-dropdown-toggle " href="#">
                            <i class="fas fa-user fa-fw"></i> ユーザー管理
                        </a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a class="nav-link " href="/admin/user">
                                    <i class="fas fa-list-alt fa-fw ml-3"></i> 管理者一覧
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="/admin/user/create">
                                    <i class="fas fa-plus fa-fw ml-3"></i> 〇〇新規登録
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown ">
                        <a class="nav-link nav-dropdown-toggle " href="#">
                            <i class="fas fa-user fa-fw"></i> サンプル管理
                        </a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a class="nav-link " href="/admin/sample">
                                    <i class="fas fa-list-alt fa-fw ml-3"></i> サンプル一覧
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="/admin/sample/create">
                                    <i class="fas fa-plus fa-fw ml-3"></i> 新規登録
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown ">
                        <a class="nav-link " href="/admin/logout">
                            <i class="fas fa-lock fa-fw"></i> ログアウト
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    @endauth
    <main class="main">
        @yield('app_bread')
        <div class="container-fluid">
            <div class="animated fadeIn">
                @yield('app_contents')
            </div>
        </div>
    </main>
</div>

<footer class="app-footer">
    <div>
        Copyright © TAKII & CO.,LTD.
    </div>
</footer>

<script src="/js/admin/jquery-3.3.1.min.js"></script>
<script src="/js/admin/popper.min.js"></script>
<script src="/js/admin/bootstrap.min.js"></script>
<script src="/js/admin/coreui.min.js" ></script>
<script src="/js/admin/jquery.dataTables.min.js"></script>
<script src="/js/admin/dataTables.bootstrap4.min.js"></script>
<script src="/js/admin/datetimepicker/jquery.datetimepicker.full.min.js"></script>
<script>
    $(function(){
        // jsのajax使う前に記述
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Datatables日本語化
        $.extend( $.fn.dataTable.defaults, {
            language: {
                "sEmptyTable":   "データはありません。",
                "sLengthMenu":   "_MENU_ 件表示",
                "sZeroRecords":  "データはありません。",
                "sInfo":         " _TOTAL_ 件中 _START_ から _END_ まで表示",
                "sInfoEmpty":    " 0 件中 0 から 0 まで表示",
                "sInfoFiltered": "（全 _MAX_ 件より抽出）",
                "sInfoPostFix":  "",
                "sSearch":       "検索:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "先頭",
                    "sPrevious": "前",
                    "sNext":     "次",
                    "sLast":     "最終"
                }
            }
        });

        if ($('#info_modal').length > 0) {
            setTimeout(function(){
                $('#info_modal').modal('show');
            },500);
        }
        if ($('#error_modal').length > 0) {
            setTimeout(function(){
                $('#error_modal').modal('show');
            },500);
        }
    })

</script>

@yield('app_js')
</body>
</html>
