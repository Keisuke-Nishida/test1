@extends('web.layouts.app')

@section('app_title')
    ルーティング定義エラー
@endsection

@section('app_contents')
    <div id="box">
        <div class="block">
            <div class="content">
                <div style="text-align: center;">
                    <h1>405 MethodNotAllowedHttpException</h1>
                    <p>ルーティングの定義がありません。</p>
                    <p style="text-align:center">
                        <a href="/">トップに戻る</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection


@extends('web.layouts.app')

@section('app_title')
    405 MethodNotAllowedHttpException
@endsection

@section('app_bread')
    <div class="row mt-2">
        <!-- パンくずリスト -->
        <div class="page__breadcrumb col-auto d-flex align-items-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item active" aria-current="page">
                        405 MethodNotAllowedHttpException
                    </li>
                </ol>
            </nav>
        </div>
    </div>
@endsection
@section('app_contents')
    <hr class="page__hr my-0">
    <!-- row -->
    <div class="article mb-5 mt-3">
        <div class="content col bg-white shadow p-3 content__card--round">
            <div class="m-5 text-center">
                <h1>405 MethodNotAllowedHttpException</h1>
                <p>ルーティングの定義がありません。</p>
                <p style="text-align:center">
                    <a href="/">トップに戻る</a>
                </p>
            </div>
        </div>
    </div>
@endsection
