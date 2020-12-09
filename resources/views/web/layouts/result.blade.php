@extends('web.layouts.app')

{{-- 追加CSS --}}
@section('app_css')
    <!-- datetimepicker -->
    <link rel="stylesheet" href="/css/web/datetimepicker/jquery.datetimepicker.min.css">
    <!-- 列固定テーブル -->
    <link rel="stylesheet" href="/css/web/fixed.table.css" />
@endsection


@section('app_title')
    送信確認
@endsection

<!-- Breadcrumb -->
@section('app_bread')
    <div class="row mt-2">
        <div class="page__breadcrumb col-auto d-flex align-items-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    @if ($isLinkToHome)
                        <li class="breadcrumb-item">
                            <a class="link__main" href="/">HOME</a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $title }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

<!-- コンテンツ -->
@section('app_contents')
    <div class="article mb-5 mt-3">
        <div class="content col bg-white shadow p-3 content__card--round">
            <div class="m-5 text-center">
                    {!! $message !!}
                    @if (isset($isLinkToLogin))
                        <br><br>
                        <p style='text-align:center'>
                            <a href='/'>ログインページに戻る</a>
                        </p>
                    @endif
            </div>
        </div>
    </div>
@endsection


