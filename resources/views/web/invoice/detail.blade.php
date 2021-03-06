<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    {{-- CSS --}}
    {{-- bootstrap4用 --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    {{-- dataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.7/css/fixedHeader.bootstrap4.min.css" />
    {{-- datetimepicker --}}
    <link rel="stylesheet" href="../../../public/css/datetimepicker/jquery.datetimepicker.min.css">
    {{-- 共通 --}}
    <link rel="stylesheet" href="../../../public/css/style.css" />
    {{-- 列固定テーブル --}}
    <link rel="stylesheet" href="../../../public/css/fixed.table.css" />
    <link rel="stylesheet" href="../../../public/css/fixed.detail.table.css" />

    {{-- fontawesome --}}
    {{-- cdn廃止予定のためdownload版を使用 --}}
    <link rel="stylesheet" href="../../../public/fontawesome-free-5.14.0-web/css/all.css" />

    <title>請求詳細 | タキイ Web情報閲覧サービス</title>
</head>

<body class="page">
    {{-- ヘッダー --}}
    <header class="header sticky-top">
        <div class="header__bachground-image">
            <div class="header__bachground-image__mask">
                <div class="d-flex flex-row align-items-center px-5 py-3 shadow">
                    {{-- ロゴ --}}
                    <div class="mr-auto">
                        <a class="header__logo my-0 mr-lg-auto" href="../home/index.blade.html">
                            <img src="../../../public/images/LOGO.png" alt="LOGO画像">
                        </a>
                    </div>

                    {{-- ログイン表示/会員メニュー --}}
                    <div class="d-flex flex-column flex-lg-row ml-5">
                        <div class="header__text d-flex align-items-center">
                            ログイン中：株式会社○○○○○○○○○○○○(ID:123456789) 様
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
                                        href="../mypage/password.blade.html"><i class="fa fa-key"
                                            aria-hidden="true"></i>
                                        パスワード変更</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item header__dropdown-menu__text header__dropdown-menu__item"
                                        href="../auth/login.blade.html"><i class="fa fa-lock" aria-hidden="true"></i>
                                        ログアウト</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container mb-5">
        {{-- パンくずリスト --}}
        <div class="d-flex mt-2">
            <div class="flex-grow-1 page__breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a class="link__main" href="../home/index.blade.html">HOME</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a class="link__main" href="../invoice/index.blade.html">請求管理</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            請求詳細
                        </li>
                    </ol>
                </nav>
            </div>

            {{-- 前へ/次へボタン --}}
            <div class="d-flex align-items-center">
                <a href="#" class="button__main--outline button__prev btn btn-sm">前へ</a>
                <a href="#" class="button__main--outline button__next btn btn-sm">次へ</a>
            </div>
        </div>

        <hr class="page__hr my-0" />

        {{-- 請求詳細一覧 --}}
        <div class="row">
            <div class="col py-3">

                <div class="d-flex flex-row overflow-auto font-1rem">
                    {{-- お届け先名 --}}
                    <div class="flex-grow-1">
                        <div class="card ">
                            <div class="card-header px-3 py-1">
                                お届け先名
                            </div>
                            <div class="card-body p-2">
                                <div class="table-responsive">
                                    <table class="main-list__table table table-borderless table-sm mb-0 bg-transparent">
                                        <tbody class="main-list__tbody">
                                            <tr>
                                                <th>住所</th>
                                                <td>○○○○○○市○○－○○－○○</td>
                                                <th>郵便番号</th>
                                                <td>012-3456</td>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <td>○○○○○○○○○○○○○○○</td>
                                                <th>電話番号</th>
                                                <td>00-0000-0000</td>
                                            </tr>
                                            <tr>
                                                <th>気付</th>
                                                <td>○○○○○部○○○○○○○○○　様</td>
                                            </tr>
                                            <tr>
                                                <th>送り先名</th>
                                                <td>株式会社○○○○○○○○○○○　様</td>
                                                <th></th>
                                                <td>
                                                    {{-- 印刷ボタン --}}
                                                    <form action="" class="ml-auto">
                                                        <button type="submit"
                                                            class="btn btn-block btn-sm button__main">印刷</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- お得意先名 --}}
                    <div class="flex-column ml-2">
                        <div class="">
                            <div class="card text-nowrap">
                                <div class="card-header px-3 py-1">お得意様名</div>
                                <div class="card-body px-3 py-2">
                                    <p class="mb-0">株式会社○○○○○○○○○○○　様</p>
                                </div>
                            </div>
                        </div>
                        {{-- 口座等 --}}
                        <div class="mt-2">
                            <table
                                class="main-list__table table table-borderless table-sm mb-0 text-nowrap bg-transparent">
                                <tr>
                                    <th>口座</th>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <th>伝票区分</th>
                                    <td>戻り追加（数量）</td>
                                </tr>
                                <tr>
                                    <th>予約区分</th>
                                    <td>予約</td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>

                {{-- 送り先データ表示 --}}
                <div class="row mt-2">
                    <div class="col">
                        <div class="table-responsive">
                            <table id="main_list" class="main-list__table table table-bordered table-sm bg-white mb-0"
                                style="width: 100%">
                                <thead class="main-list__thead th-sm">
                                    <tr>
                                        <th class="processing_date">処理日</th>
                                        <th class="sales_number">売上No</th>
                                        <th class="order_confirmation_date">受注確定日</th>
                                        <th class="order_number">受注No</th>
                                        <th class="shipment_date">出荷日</th>
                                        <th class="instruction_number">指図No</th>
                                    </tr>
                                </thead>
                                <tbody class="main-list__tbody">
                                    <tr>
                                        <td class="processing_date fixed-column-left-1">2020/01/01</td>
                                        <td class="sales_number">0123456</td>
                                        <td class="order_confirmation_date">2020/01/01</td>
                                        <td class="order_number">0123456</td>
                                        <td class="shipment_date">2020/01/01</td>
                                        <td class="instruction_number">ABCDE01234</td>
                                    </tr>
                                </tbody>
                                <thead class="main-list__thead th-sm">
                                    <tr>
                                        <th class="shipment_location">出荷場所</th>
                                        <th class="number_of_units">個口数</th>
                                        <th class="freight_category">運賃区分</th>
                                        <th class="transportation_method">運送方法</th>
                                        <th class="transportation_designation">運送指定</th>
                                        <th class="transportation_service_name">扱便名</th>
                                    </tr>
                                </thead>
                                <tbody class="main-list__tbody">
                                    <tr>
                                        <td class="shipment_location fixed-column-left-1">本社</td>
                                        <td class="number_of_units">00</td>
                                        <td class="freight_category">*****</td>
                                        <td class="transportation_method">自動車便</td>
                                        <td class="transportation_designation">*****</td>
                                        <td class="transportation_service_name">クロネコメール便</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- 詳細一覧テーブル --}}
                <div class="row mt-2">
                    <div class="col">
                        <div id="" class="main-list__detail__table">
                            {{-- 表 --}}
                            <table id="main_list" class="main-list__table table table-sm bg-white mb-0"
                                style="width: 100%">
                                {{-- 表頭 --}}
                                <thead class="main-list__thead main-list__detail__thead th-sm">
                                    <tr class="fixed-header-0">
                                        <th class="number">No.</th>
                                        <th class="item_name">商品名</th>
                                        <th class="item_code">商品コード</th>
                                        <th class="packaging_unit_code">包装単位</th>
                                        <th class="shipments">出荷数量</th>
                                        <th class="sales_price_per_unit">売上単価</th>
                                        <th class="unit_price_after_discount">歩引き後単価</th>
                                        <th class="sales_amount">売上金額</th>
                                    </tr>
                                </thead>
                                {{-- 表体 --}}
                                <tbody id="main-list__detail__tbody" class="main-list__tbody main-list__detail__tbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- 伝票番号と総計等 --}}
                <div class="d-flex flex-column flex-lg-row mt-2">
                    {{-- 伝票番号 --}}
                    <div class="d-flex d-lg-inline-flex">
                        <table class="main-list__table table table-bordered table-sm bg-white mb-0">
                            <tbody class="main-list__tbody th-sm">
                                <tr>
                                    <th>伝票番号</th>
                                    <td>0000-0000-0000-0000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- 金額類 --}}
                    <div class="d-flex flex-column flex-lg-row ml-lg-auto">
                        <div class="d-flex d-lg-inline-flex">
                            <table class="main-list__table table table-bordered table-sm bg-white mb-0">
                                <tbody class="main-list__tbody th-sm">
                                    <tr>
                                        <th>送料</th>
                                        <td>&yen; 999,999,999</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex d-lg-inline-flex">
                            <table class="main-list__table table table-bordered table-sm bg-white mb-0">
                                <tbody class="main-list__tbody th-sm">
                                    <tr>
                                        <th>合計</th>
                                        <td>&yen; 999,999,999</td>
                                    </tr>
                                    <tr>
                                        <th>消費税</th>
                                        <td>&yen; 999,999,999</td>
                                    </tr>
                                    <tr>
                                        <th>総計</th>
                                        <td>&yen; 999,999,999</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <hr class="page__hr my-0" />

        {{-- 請求管理へ戻るボタン --}}
        <div class="d-flex justify-content-center mt-2">
            <div class="text-nowrap">
                <a href="./index.blade.html" class="button__main--outline btn btn-sm px-3">請求管理へ戻る</a>
            </div>
        </div>
    </div>

    <footer class="footer mt-auto">
        <div class="container">
            <div class="row m-0">
                <div class="col text-center p-2">
                    <a class="footer__text text-white">Copyright © TAKII & CO.,LTD.</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- JavaScript --}}
    {{-- jQuery本体 --}}
    <script src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
    {{-- dataTables --}}
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.7/js/dataTables.fixedHeader.min.js"></script>
    {{-- datetimepicker --}}
    <script src="../../../public/js/datetimepicker/jquery.datetimepicker.full.min.js"></script>
    {{-- 共有 --}}
    <script src="../../../public/js/main.js"></script>
    <script src="../../../public/js/invoice.js"></script>
    <script src="../../../public/js/data_invoice.js"></script>
    {{-- ヘッダー固定テーブル --}}
    <script src="../../../public/js/fixed.detail.table.js"></script>

</body>

</html>
