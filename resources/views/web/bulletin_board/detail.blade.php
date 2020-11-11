<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <!-- CSS -->
    <!-- bootstrap4用 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <!-- dataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.7/css/fixedHeader.bootstrap4.min.css" />
    <!-- datetimepicker -->
    <link rel="stylesheet" href="../../../public/css/datetimepicker/jquery.datetimepicker.min.css">
    <!-- 共通 -->
    <link rel="stylesheet" href="../../../public/css/style.css" />
    <!-- 列固定テーブル -->
    <link rel="stylesheet" href="../../../public/css/fixed.table.css" />

    <!-- fontawesome -->
    <!-- cdn廃止予定のためdownload版を使用 -->
    <link rel="stylesheet" href="../../../public/fontawesome-free-5.14.0-web/css/all.css" />

    <title>湖南市市民産業交流施設「ここぴあ」オープンの模様をご紹介いたします | タキイ Web情報閲覧サービス</title>
</head>

<body class="page">
    <!-- ヘッダー -->
    <header class="header sticky-top">
        <div class="header__bachground-image">
            <div class="header__bachground-image__mask">
                <div class="d-flex flex-row align-items-center px-5 py-3 shadow">
                    <!-- ロゴ -->
                    <div class="mr-auto">
                        <a class="header__logo my-0 mr-lg-auto" href="../home/index.blade.html">
                            <img src="../../../public/images/LOGO.png" alt="LOGO画像">
                        </a>
                    </div>

                    <!-- ログイン表示/会員メニュー -->
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

        <!-- パンくずリスト -->
        <div class="row mt-2">
            <!-- ページタイトル -->
            <!-- <div class="col col-lg-2 d-flex align-items-end">
                    <h1 class="page__title">HOME</h1>
                </div> -->
            <div class="page__breadcrumb col col-lg-10 d-flex align-items-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item">
                            <a class="link__main" href="../home/index.blade.html">HOME</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <a class="link__main" href="./list.blade.html">掲示板一覧</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            2020年秋 種特集号 vol.50
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <hr class="page__hr my-0">

        <!-- row -->
        <div class="row article content">
            <div class="col py-3">
                <div class="col bg-white shadow content__card--round p-5">
                    <div class="row">
                        <div class="col-3"><img src="../../../public/images/bbs_image1.png" width="100%"></div>
                        <div class="col-9">
                            <h2 class="m-0 ml-2 mb-1">2020年秋 種特集号 vol.50</h2>
                            <small class="article-list__date ml-2 mb-2"><i class="far fa-clock fa-fw"></i>
                                2020.01.01</small>
                            <p class="article ml-2 mt-2 text__sub">
                                ・特別付録　家庭菜園の基礎知識　野菜作りをはじめよう<br>
                                ・50号記念特別企画インタビュー<br>
                                ・タキイ秋の品種カタログ<br>
                            </p>
                            <p class="article ml-2 mt-2 text-center">
                                <button class="btn button__main col-6">ダウンロード</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col content__bottom__link text-center mt-4">
                <a class="link__main" href="./list.blade.html">> 掲示板一覧へ</a>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="contariner">
            <div class="row m-0">
                <div class="col text-center p-2">
                    <small class="footer__text text-white">Copyright © TAKII & CO.,LTD.</small>
                </div>
            </div>
        </div>
    </footer>

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
    <script src="../../../public/js/datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <!-- 共有 -->
    <script src="../../../public/js/main.js"></script>
</body>

</html>