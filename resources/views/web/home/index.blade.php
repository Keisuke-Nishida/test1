@extends('web.layouts.app')

@section('app_title')
    HOME
@endsection

{{-- Breadcrumb--}}
@section('app_bread')
    <div class="row mt-2">
        <div class="page__breadcrumb col col-lg-10 d-flex align-items-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item active" aria-current="page">HOME</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

{{-- コンテンツ --}}
@section('app_contents')
    <div class="row p-3">
        <div class="col-lg">
            <div class="row">
                <!-- 請求管理 -->
                <a class="content btn btn-link col shadow px-3 py-5 content__card--round text-center"
                   href="/invoice">
                    <i class="content__icon far fa-file-alt fa-4x"></i><br>
                    <h2 class="content__title mt-3">請求管理</h2>
                    <p class="content__info m-0">99</p>
                </a>
                <!-- 出荷管理 -->
                <a class="content btn btn-link col shadow px-3 py-5 ml-4 content__card--round text-center"
                   href="/shipment">
                    <i class="content__icon fas fa-truck fa-4x"></i><br>
                    <h2 class="content__title mt-3">出荷管理</h2>
                    <p class="content__info m-0">99</p>
                </a>
            </div>
        </div>

        <div class="col-lg">
            <!-- お知らせ -->
            <div class="row">
                <div class="col bg-white shadow p-4 mt-5 mt-lg-0 ml-lg-5 content__card--round">
                    <h2 class="content__title p-2">最新のお知らせ</h2>
                    <ul class="article-list list-unstyled mt-2">
                        <li class="article-list__item media  p-2 mt-2">
                            <a class="text-decoration-none" href="../notice/detail.blade.html">
                                <div class="media-body">
                                    <h3 class="article-list__title m-0">湖南市市民産業交流施設「ここぴあ」...</h3>
                                    <small class="article-list__date"><i class="far fa-clock fa-fw"></i>
                                        2020.01.01</small>
                                    <p class="article-list__text mb-0 text-secondary" style="font-size: 0.9em;">
                                        本誌『タキイ最前線』2017年秋種特集号より連載スタートした「ここぴあ通信」...
                                    </p>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="content__bottom__link text-center">
                        <a class="link__main" href="/notice">> 過去のお知らせ一覧へ</a>
                    </div>
                </div>
            </div>

            <!-- 掲示板 -->
            <div class="row">
                <div class="col bg-white shadow p-4 mt-4 ml-lg-5 content__card--round">
                    <h2 class="content__title p-2">最新の掲示板</h2>
                    <ul class="article-list list-unstyled mt-2">
                        <a class="text-decoration-none" href="../bulletin_board/detail.blade.html">
                            <li class="article-list__item media p-2 mt-2">
                                <div class="col-3">
                                    <img src="/images/bbs_image1.png" width="100%">
                                </div>
                                <div class="col-9">
                                    <h3 class="article-list__title m-0"><u>2020年秋 種特集号 vol.50</u></h3>
                                    <small class="article-list__date"><i class="far fa-clock fa-fw"></i>
                                        2020.10.01</small>
                                    <p class="article-list__text mb-0 mb-0 text-secondary"
                                       style="font-size: 0.9em;">
                                        ・特別付録　家庭菜園の基礎知識　野菜作りをはじめよう...
                                    </p>
                                </div>
                            </li>
                        </a>
                    </ul>
                    <div class="content__bottom__link text-center">
                        <a class="link__main" href="/bulletin_board">> 過去の掲示板一覧へ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
