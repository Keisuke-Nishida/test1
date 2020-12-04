@extends('web.layouts.app')

{{-- 追加CSS --}}
@section('app_css')
@endsection


@section('app_title')
    お知らせ一覧
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
                        お知らせ一覧
                    </li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

<!-- コンテンツ -->
@section('app_contents')
    <div class="row article">
        <div class="col py-3">
            <div class="content bg-white shadow content__card--round p-4">
                <ul class="article-list list-unstyled">
                    @foreach ($list as $data)
                        <a class="text-decoration-none" href="/notice/detail/{{ $data->id }}">
                            <li class="article-list__item media p-4">
                                <div class="media-body">
                                    <h3 class="article-list__title m-0">
                                        <u>{{ Str::limit($data->title, $limit_title_length) }}</u>
                                    </h3>
                                    <small class="article-list__date d-block mt-3">
                                        <i class="far fa-clock fa-fw"></i>{{ $data->start_time }}
                                    </small>
                                    <p class="article-list__text mb-0 text-secondary mt-3" style="font-size: 0.9em;">
                                        {{ Str::limit($data->body, $limit_body_length) }}
                                    </p>
                                </div>
                            </li>
                        </a>
                    @endforeach
                </ul>

                <!-- ページネーション -->
                <div class="content__bottom__link text-center">
                    <div class="content__bottom__link pagination justify-content-center justify-content-md-end">
                        {{ $list->onEachSide(1)->links('web.layouts.components.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- 追加JSファイル --}}
@section('app_js')
@endsection
