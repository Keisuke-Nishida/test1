<!DOCTYPE html>
<html lang="ja">
<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <link rel="stylesheet" href="/css/admin/coreui.min.css">
    <link rel="stylesheet" href="/css/admin/fontawesome/all.css">
    <link rel="stylesheet" href="/css/admin/style.css">

    <title>@yield('app_title') | {{ env('APP_NAME') }}</title>
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
<header class="app-header navbar">
    <div class="system-title">{{ env('APP_NAME') }}</div>
    <ul class="nav navbar-nav ml-auto"></ul>
</header>
<div class="container">
    <div class="row justify-content-center" style="margin-top: 15%">
        @yield('app_contents')
    </div>
</div>
<script src="/js/admin/jquery-3.3.1.min.js"></script>
<script src="/js/admin/popper.min.js"></script>
<script src="/js/admin/bootstrap.min.js"></script>
<script src="/js/admin/coreui.min.js" ></script>

</body>
</html>
