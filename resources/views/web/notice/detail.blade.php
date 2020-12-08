@extends('web.layouts.app')

{{-- 追加CSS --}}
@section('app_css')
@endsection


@section('app_title')
    {{ $data->title }}
@endsection

<!-- Breadcrumb -->
@section('app_bread')
    <div class="row mt-2">
        <div class="page__breadcrumb col col-lg-10 d-flex align-items-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item">
                        <a class="link__main" href="/">HOME</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a class="link__main" href="/notice">お知らせ一覧</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ Str::limit($data->title, $limit_title_length) }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

<!-- コンテンツ -->
@section('app_contents')
    <!-- 記事詳細 -->
    <div class="article row">
        <div class="col py-3">
            <div class="content bg-white shadow content__card--round p-5">
                <h2 class="article-list__title m-0">{{ $data->title }}</h2>
                <small class="article-list__date">
                    <i class="far fa-clock fa-fw"></i> {{ $data->start_time }}
                </small>
                <p class="article-list__text mt-4 text__sub">{{ $data->body }}</p>
            </div>
        </div>
    </div>

    <!-- お知らせ一覧へリンク -->
    <div class="row">
        <div class="col content__bottom__link text-center mt-4">
            <a class="link__main" href="/notice">> お知らせ一覧へ</a>
        </div>
    </div>
@endsection

{{-- 追加JSファイル --}}
@section('app_js')
@endsection
