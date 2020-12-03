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
                    @if (isset($check_result) && $check_result == "resultEmail")
                        <li class="breadcrumb-item active" aria-current="page">
                            {{-- 送信確認 --}}
                            {{ $title }}
                        </li>
                    @elseif (isset($check_result) && $check_result == "confirmEmail")
                        <li class="breadcrumb-item">
                            <a class="link__main" href="/">HOME</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{-- メッセージ --}}
                            {{ $title }}
                        </li>
                    @elseif (isset($check_result)&& $check_result == "changePassword")
                        <li class="breadcrumb-item">
                            <a class="link__main" href="/">HOME</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $title }}
                        </li>
                    @else
                        <li class="breadcrumb-item">
                            <a class="link__main" href="/">HOME</a>
                        </li>
                    @endif

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
                @if (isset($check_result) && $check_result == "resultEmail")
                    {!! $message !!}
                @elseif (isset($check_result) && $check_result == "confirmEmail")
                    {{ $message }}
                @elseif (isset($check_result)&& $check_result == "changePassword")
                    {{ $message }}
                @else

                @endif
            </div>
        </div>
    </div>
@endsection


