<!DOCTYPE html>
<html class="no-js" lang="ja">
<head>
    <meta charset="utf-8" />
    <title>@yield('app_title') | {{ env('APP_NAME') }}</title>
    <meta name="description" content="{{ env('APP_NAME') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://unpkg.com/@coreui/coreui@2.1.12/dist/css/coreui.min.css" integrity="sha384-CV/XOmWmdqGAuBEWrqJ3CM7BmhB3uaJDuNDGVsusdXb/6/kdU159Yt3IezfzWWX3" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs4/dt-1.10.22/r-2.2.6/sl-1.3.1/datatables.min.css" integrity="sha384-K3I/E0puv+yTOSbpjz468Q/yUllDGwgnhET3a/dwkvW0aSflRQZuedm3sNuCtaPu" crossorigin="anonymous" />
    <!-- datetimepicker -->
    <link rel="stylesheet" href="{{asset('css/web/datetimepicker/jquery.datetimepicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/admin/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="theme-color" content="#fafafa" />
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
    <input type="hidden" name="_token" id="csrf-token" value="{{ csrf_token() }}" />
    <header class="app-header navbar">
        <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show"><span class="navbar-toggler-icon"></span></button>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show"><span class="navbar-toggler-icon"></span></button>
        <div class="system-title">{{ env('APP_NAME') }}</div>
        @auth
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item dropdown mr-3">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> {{ session('login_id') . '  様' }}</a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#"><i class="fas fa-user-circle"></i> {{ Util::langtext('MYPAGE_L_001') }}</a>
                    <a class="dropdown-item" href="{{ route('admin/logout') }}"><i class="fa fa-lock"></i> {{ Util::langtext('LOGOUT_L_001') }}</a>
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
                        <a class="nav-link<?php echo (Util::isMenuItemActive('dashboard') || Util::getCurrentScreen() == '') ? ' active' : ''; ?>" href="{{ route('admin/index') }}"><i class="fas fa-home fa-lg"></i> {{ Util::langtext('SIDEBAR_LI_001') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php echo (Util::isMenuItemActive('user')) ? ' active' : ''; ?>" href="{{ route('admin/user') }}"><i class="fas fa-user fa-fw"></i> {{ Util::langtext('SIDEBAR_LI_002') }}</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link<?php echo (Util::isMenuItemActive('customer')) ? ' active' : ''; ?>" href="{{ route('admin/customer') }}"><i class="far fa-building fa-fw"></i> {{ Util::langtext('SIDEBAR_LI_003') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php echo (Util::isMenuItemActive('role')) ? ' active' : ''; ?>" href="{{ route('admin/role_menu') }}"><i class="fas fa-user-shield fa-fw"></i> {{ Util::langtext('SIDEBAR_LI_004') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php echo (Util::isMenuItemActive('notice_data')) ? ' active' : ''; ?>" href="{{ route('admin/notice_data') }}"><i class="fas fa-bell fa-fw"></i> {{ Util::langtext('SIDEBAR_LI_005') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php echo (Util::isMenuItemActive('bulletin_board')) ? ' active' : ''; ?>" href="{{ route('admin/bulletin_board') }}"><i class="fas fa-chalkboard fa-fw"></i> {{ Util::langtext('SIDEBAR_LI_006') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php echo (Util::isMenuItemActive('shipment')) ? ' active' : ''; ?>" href="#"><i class="fas fa-truck fa-fw"></i> {{ Util::langtext('SIDEBAR_LI_007') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php echo (Util::isMenuItemActive('invoice')) ? ' active' : ''; ?>" href="#"><i class="fas fa-file-alt fa-fw"></i> {{ Util::langtext('SIDEBAR_LI_008') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?php echo (Util::isMenuItemActive('schedule')) ? ' active' : ''; ?>" href="{{ route('admin/schedule') }}"><i class="far fa-clock fa-fw"></i> {{ Util::langtext('SIDEBAR_LI_009') }}</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link<?php echo (Util::isMenuItemActive('message')) ? ' active' : ''; ?>" href="{{ route('admin/message') }}"><i class="far fa-envelope fa-fw"></i> {{ Util::langtext('SIDEBAR_LI_010') }}</a>
                    </li>
                </ul>
            </nav>
        </div>
        @endauth
        <main class="main">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-weight-bold">@yield('app_bread')</li>
            </ol>
            <div class="container-fluid">
                <div class="animated fadeIn">
                    <div class="row">
                        <div class="col-12">
                            @yield('app_contents')
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <footer class="app-footer">
        <div>Copyright &#169; TAKII &amp; CO.,LTD.</div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@coreui/coreui@2.1.12/dist/js/coreui.min.js" integrity="sha384-TxyxA9MKinm4ESUqZNLJzZgM55aOc7BVSDytm867dxHLhyGwmATEv3IKVbYFohrb" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/v/bs4/dt-1.10.22/r-2.2.6/sl-1.3.1/datatables.min.js" integrity="sha384-c56bHgB/zM/a5D8wfSJPFcmznDhMyKIs+5b5/foJ/bgcoPTD9/gVJ2maDD2WHqEm" crossorigin="anonymous"></script>
    <script src="{{ asset('js/admin/common.js') }}"></script>
    {{-- datetimepicker --}}
<script src="{{ asset('js/admin/datetimepicker/jquery.datetimepicker.full.min.js')}}"></script>

    <script>
        var DATA_TABLE_EMPTY_TEXT = "<?php echo Util::langtext('DATA_TABLE_EMPTY_TEXT'); ?>";
        var DATA_TABLE_INFO_TEXT = "<?php echo Util::langtext('DATA_TABLE_INFO_TEXT'); ?>";
        var DATA_TABLE_INFO_EMPTY_TEXT = "<?php echo Util::langtext('DATA_TABLE_INFO_EMPTY_TEXT'); ?>";
        var DATA_TABLE_LENGTH_TEXT = "<?php echo Util::langtext('DATA_TABLE_LENGTH_TEXT'); ?>";
        var DATA_TABLE_PAGINATE_FIRST = "<?php echo Util::langtext('DATA_TABLE_PAGINATE_FIRST'); ?>";
        var DATA_TABLE_PAGINATE_PREVIOUS = "<?php echo Util::langtext('DATA_TABLE_PAGINATE_PREVIOUS'); ?>";
        var DATA_TABLE_PAGINATE_NEXT = "<?php echo Util::langtext('DATA_TABLE_PAGINATE_NEXT'); ?>";
        var DATA_TABLE_PAGINATE_LAST = "<?php echo Util::langtext('DATA_TABLE_PAGINATE_LAST'); ?>";
        var NO_DATA_SELECTED = "<?php echo Util::langtext('NO_DATA_SELECTED'); ?>";
    </script>
    @if ($page)
    <script src="{{ asset('js/admin/' . $page . '.js') }}"></script>
    @endif
</body>
</html>
