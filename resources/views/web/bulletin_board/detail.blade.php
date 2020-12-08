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
                        <a class="link__main" href="/bulletin_board">掲示板一覧</a>
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
    <div class="row article">
        <div class="col py-3">
            <div class="col bg-white shadow content__card--round p-5">
                <div class="row">
                    <div class="col-3">
                        <img src="{{ $data->image_url }}" width="100%">
                    </div>
                    <div class="col-9">
                        <h2 class="article-list__title m-0 ml-2 mb-1">{{ $data->title }}</h2>
                        <small class="article-list__date ml-2 mb-2">
                            <i class="far fa-clock fa-fw"></i> {{ $data->start_time }}
                        </small>
                        <p class="article-list__text  ml-2 mt-2 text__sub">{{ $data->body }}</p>

                        @if (isset($data->file_url))
                            <form action="/bulletin_board/download/{{ $data->id }}">
                                <div class="article ml-2 mt-2 text-center">
                                    <button type="submit" class="btn button__main col-6">ダウンロード</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 掲示板一覧へへリンク -->
    <div class="row">
        <div class="col content__bottom__link text-center mt-4">
            <a class="link__main" href="/bulletin_board">> 掲示板一覧へ</a>
        </div>
    </div>
@endsection

{{-- 追加JSファイル --}}
@section('app_js')
@endsection
