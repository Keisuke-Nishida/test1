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

    <title>送信確認 | タキイ Web情報閲覧サービス</title>
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

                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row mt-2">
            <!-- パンくずリスト -->
            <div class="page__breadcrumb col-auto d-flex align-items-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item active" aria-current="page">
                            送信確認
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <hr class="page__hr my-0">
        <!-- row -->
        <div class="article mb-5 mt-3">
            <div class="content col bg-white shadow p-3 content__card--round">
                <div class="m-5 text-center">
                    ご入力いただいたアドレスへメールを送信いたしました。<br>
                    メールの内容をご確認の上、利用登録を行ってください。
                </div>
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
