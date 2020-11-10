@extends('web.layouts.app')

@section('app_title')
    403 Forbidden
@endsection

@section('app_bread')
    <div class="row mt-2">
        <!-- パンくずリスト -->
        <div class="page__breadcrumb col-auto d-flex align-items-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item active" aria-current="page">
                        403 Forbidden
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
                <h1>403 Forbidden</h1>
                <p>権限がありません。</p>
                <p style="text-align:center">
                    <a href="/">トップに戻る</a>
                </p>
            </div>
        </div>
    </div>
@endsection
