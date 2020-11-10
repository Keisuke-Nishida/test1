@extends('web.layouts.app')

{{-- 追加CSS --}}
@section('app_css')
    <!-- datetimepicker -->
    <link rel="stylesheet" href="/css/web/datetimepicker/jquery.datetimepicker.min.css">
    <!-- 列固定テーブル -->
    <link rel="stylesheet" href="/css/web/fixed.table.css" />
@endsection


@section('app_title')
    請求管理
@endsection

<!-- Breadcrumb -->
@section('app_bread')
    <div class="row mt-2">
        <div class="page__breadcrumb col-auto d-flex align-items-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item">
                        <a class="link__main" href="../home/index.blade.html">HOME</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        請求管理
                    </li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

<!-- コンテンツ -->
@section('app_contents')
    <!-- 請求管理一覧 -->
    <div class="row">
        <div class="col py-3">

            <!-- 絞り込み、検索欄 -->
            <div class="accordion" id="detailedSearch">
                <div class="card border-bottom">
                    <div class="card-header" id="detailedSearchHeading">
                        <div class="d-flex">
                            <!-- 検索条件表示欄 -->
                            <div class="align-self-center">
                                <!-- 詳細検索開閉ボタン -->
                                <button id="detailedSearchButton"
                                    class="button__main button__detail-search btn text-nowrap" type="button"
                                    data-toggle="collapse" data-target="#detailedSearchCollapse"
                                    aria-expanded="false" aria-controls="detailedSearchCollapse">
                                    詳細検索
                                </button>
                            </div>

                            <div class="d-flex flex-wrap bg-white px-3 py-2 ml-3">
                                <div class="text-nowrap flex-fill">
                                    <label class="mb-0" for="">処理日：</label>
                                    <label class="ml-2 mb-0 font-weight-normal" for="">2020/10/01～2020/10/01</label>
                                </div>
                                <div class="text-nowrap flex-fill">
                                    <label class="mb-0" for="">受注確定日：</label>
                                    <label class="ml-2 mb-0 font-weight-normal" for="">2020/10/01～</label>
                                </div>
                                <div class="text-nowrap flex-fill">
                                    <label class="mb-0" for="">出荷日：</label>
                                    <label class="ml-2 mb-0 font-weight-normal" for="">～2020/10/01</label>
                                </div>
                                <div class="text-nowrap flex-fill">
                                    <label class="mb-0" for="">フリーワード：</label>
                                    <label class="ml-2 mb-0 font-weight-normal" for="">テスト　テスト　テスト</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 検索欄 -->
                    <div id="detailedSearchCollapse" class="collapse" aria-labelledby="detailedSearchHeading"
                        data-parent="#detailedSearch">
                        <div class="card-body">
                            <form action="" method="post" class="">
                                <div class="form-inline align-items-center form-row">
                                    <!-- 処理日 -->
                                    <div class="col-12 col-lg-6">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text"
                                                    for="processing_date_start">処理日</label>
                                            </div>
                                            <input type="text" class="form-control" id="processing_date_start"
                                                name="processing_date_start" placeholder="YYYY/MM/DD" />
                                            <div class="input-group-append input-group-prepend">
                                                <div class="input-group-text">～</div>
                                            </div>
                                            <input type="text" class="form-control" id="processing_date_end"
                                                name="processing_date_end" placeholder="YYYY/MM/DD" />
                                        </div>
                                    </div>

                                    <!-- 出荷日 -->
                                    <div class="col-12 col-lg-6">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text"
                                                    for="shipment_date_start">出荷日</label>
                                            </div>
                                            <input type="text" class="form-control" id="shipment_date_start"
                                                name="shipment_date_start" placeholder="YYYY/MM/DD" />
                                            <div class="input-group-append input-group-prepend">
                                                <div class="input-group-text">～</div>
                                            </div>
                                            <input type="text" class="form-control" id="shipment_date_end"
                                                name="shipment_date_end" placeholder="YYYY/MM/DD" />
                                        </div>
                                    </div>

                                    <!-- 受注確定日 -->
                                    <div class="col-12 col-lg-6 mt-lg-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text"
                                                    for="order_confirmed_date_start">受注確定日</label>
                                            </div>
                                            <input type="text" class="form-control" id="order_confirmed_date_start"
                                                name="order_confirmed_date_start" placeholder="YYYY/MM/DD" />
                                            <div class="input-group-append input-group-prepend">
                                                <div class="input-group-text">～</div>
                                            </div>
                                            <input type="text" class="form-control" id="order_confirmed_date_end"
                                                name="order_confirmed_date_end" placeholder="YYYY/MM/DD" />
                                        </div>
                                    </div>

                                    <!-- フリーワード -->
                                    <div class="col-12 col-lg-6 mt-lg-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" for="freeword"><i
                                                        class="fa fa-search fa-fw" aria-hidden="true"></i></label>
                                            </div>
                                            <input type="text" class="form-control" id="freeword" name="freeword"
                                                placeholder="フリーワード" />
                                        </div>
                                    </div>
                                </div>

                                <!-- ボタン -->
                                <div class="row mt-2">
                                    <div class="col d-flex justify-content-center">
                                        <button id="searchButton" type="button"
                                            class="button__main button__search btn">
                                            検索
                                        </button>
                                        <button id="clearButton" type="button"
                                            class="button__main--outline button__clear btn ml-2">
                                            クリア
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-row mt-3">
                <!-- 印刷ボタン -->
                <form action="">
                    <div class="">
                        <button type="button" class="button__main button__print btn">
                            印刷
                        </button>
                    </div>
                </form>

                <!-- csvダウンロードボタン -->
                <form action="">
                    <div class="ml-3">
                        <button type="button" class="button__main button__csv btn">
                            CSV出力
                        </button>
                    </div>
                </form>

                <!-- 口座選択 -->
                <div id="accountNumber" class="accountNumbers ml-auto d-flex align-items-center">
                    <span>口座選択</span>
                    <div class="custom-control custom-checkbox custom-control-inline cursor-pointer ml-3">
                        <input type="checkbox" id="accountNumber1" name="accountNumber1"
                            class="custom-control-input">
                        <label class="custom-control-label cursor-pointer" for="accountNumber1">1</label>
                    </div>
                    <div class="custom-control custom-checkbox custom-control-inline cursor-pointer">
                        <input type="checkbox" id="accountNumber2" name="accountNumber2"
                            class="custom-control-input">
                        <label class="custom-control-label cursor-pointer" for="accountNumber2">2</label>
                    </div>
                    <div class="custom-control custom-checkbox custom-control-inline cursor-pointer">
                        <input type="checkbox" id="accountNumber3" name="accountNumber3"
                            class="custom-control-input">
                        <label class="custom-control-label cursor-pointer" for="accountNumber3">3</label>
                    </div>
                </div>
            </div>

            <!-- 件数表示 -->
            <div class="row dataTables_wrapper">
                <div class="col mt-4 mb-0">
                    <div class="dataTables_length">
                        <label class="d-flex align-item-center">
                            <select name="" id="" class="custom-select custom-select-sm mr-2">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            件表示
                        </label>
                    </div>
                </div>
            </div>

            <!-- テーブル -->
            <div class="row">
                <div class="col mt-2">
                    <!-- 表 -->
                    <div class="table-responsive">
                        <div class="main-list__table--fixed-wrapper">
                            <table id="main_list"
                                class="main-list__table main-list__table--fixed table table-bordered table-sm mb-0"
                                data-fixed_table="true">
                                <!-- 表頭 -->
                                <thead class="main-list__thead">
                                    <tr class="" role="row">
                                        <th class="fixed-column--left cursor-pointer" id="checkAllArea">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="checkAll">
                                                <label class="custom-control-label cursor-pointer"
                                                    for="checkAll"></label>
                                            </div>
                                        </th>
                                        <th class="shipment_name fixed-column--left">送り先様</th>
                                        <th class="processing_date">処理日</th>
                                        <th class="sales_number">売上No.</th>
                                        <th class="order_confirmed_date">受注確定日</th>
                                        <th class="order_number">受注No.</th>
                                        <th class="shipment_date">出荷日</th>
                                        <th class="instruction_number">指図No.</th>
                                        <th class="voucher_category">伝票区分</th>
                                        <th class="reservation_category">予約区分</th>
                                        <th class="fixed-column--right">操作</th>
                                    </tr>
                                </thead>
                                <!-- 表体 -->
                                <tbody id="main-list__invoice__tbody" class="main-list__tbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- テーブルフッター -->
            <div class="row mt-2">
                <!-- 件数表示 -->
                <div class="col-sm-12 col-md-5">
                    <div class="d-flex justify-content-center justify-content-md-start" id="main_list_info"
                        role="status" aria-live="polite">
                        25 件中 1 から 10 まで表示
                    </div>
                </div>
                <!-- ページネーション -->
                <div class="col-sm-12 col-md-7">
                    <div class="paging_simple_numbers" id="main_list_paginate">
                        <ul class="pagination justify-content-center justify-content-md-end">
                            <li class="paginate_button page-item previous disabled" id="main_list_previous">
                                <a href="#" aria-controls="main_list" data-dt-idx="0" tabindex="0"
                                    class="page-link">前</a>
                            </li>
                            <li class="paginate_button page-item active">
                                <a href="#" aria-controls="main_list" data-dt-idx="1" tabindex="0"
                                    class="page-link">1</a>
                            </li>
                            <li class="paginate_button page-item">
                                <a href="#" aria-controls="main_list" data-dt-idx="2" tabindex="0"
                                    class="page-link">2</a>
                            </li>
                            <li class="paginate_button page-item">
                                <a href="#" aria-controls="main_list" data-dt-idx="3" tabindex="0"
                                    class="page-link">3</a>
                            </li>
                            <li class="paginate_button page-item next" id="main_list_next">
                                <a href="#" aria-controls="main_list" data-dt-idx="4" tabindex="0"
                                    class="page-link">次</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="page__hr my-0" />

    <!-- ホームへ戻るボタン -->
    <div class="d-flex justify-content-center mt-2">
        <div class="text-nowrap">
            <a href="../home/index.blade.html" class="button__main--outline btn btn-sm px-3">ホームへ戻る</a>
        </div>
    </div>

    {{-- 送り先詳細モーダル --}}
    @include('web.layouts.components.modal.detail')
    {{-- ローディングスピナー --}}
    @include('web.layouts.components.loading')

@endsection

{{-- 追加JSファイル --}}
@section('app_js')
    <script src="/js/web/data_invoice.js"></script>
    <script src="/js/web/invoice.js"></script>
    <!-- ヘッダーと列固定テーブル -->
    <script src="/js/web/fixed.table.js"></script>
@endsection



