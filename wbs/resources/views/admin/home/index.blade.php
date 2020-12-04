@extends('admin.layouts.app')

@section('app_title')
    HOME
@endsection

@section('app_bread')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">HOME</li>
    </ol>
@endsection

@section('app_contents')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    メニュー
                </div>
                <div class="card-body main-menu">
                    <div class="row mb-2">
                        <div class="col-md-3"><a href="/admin">〇〇管理</a></div>
                        <div class="col-md-9">〇〇管理の説明を記述</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3"><a href="/admin">〇〇管理</a></div>
                        <div class="col-md-9">〇〇管理の説明を記述</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3"><a href="/admin">〇〇管理</a></div>
                        <div class="col-md-9">〇〇管理の説明を記述</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3"><a href="/admin">〇〇管理</a></div>
                        <div class="col-md-9">〇〇管理の説明を記述</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
