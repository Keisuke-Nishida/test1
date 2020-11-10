@extends('admin.layouts.app')

@section('app_title')
    サンプル登録
@endsection

@section('app_bread')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/sample">サンプル</a></li>
        <li class="breadcrumb-item">サンプル{{ $register_mode == "create" ? '新規登録' : '編集' }}</li>
    </ol>
@endsection

@section('app_contents')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">サンプル{{ $register_mode == "create" ? '新規登録' : '編集' }}<span class="text-danger">※は必須入力</span></div>
                <div class="card-body">
                    <form class="form-horizontal" method="post" enctype="multipart/form-data" id="main_form" action="/admin/sample/save">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">名前<span class="text-danger">※</span></label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" placeholder="placeholder" name="name" value="{{ old('name', $data["name"]) }}">
                                        @include('admin.layouts.components.error_message', ['title' => 'name'])
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">サンプル１<span class="text-danger">※</span></label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" placeholder="sample1" name="sample1" value="{{ old('sample1', $data["sample1"]) }}">
                                        @include('admin.layouts.components.error_message', ['title' => 'sample1'])
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">サンプル２</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" placeholder="sample2" name="sample2" value="{{ old('sample2', $data["sample2"]) }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">サンプル３</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" placeholder="sample3" name="sample3" value="{{ old('sample3', $data["sample3"]) }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">サンプル時刻</label>
                                    <div class="col-md-9">
                                        <input class="form-control datepicker" type="text" placeholder="placeholder" name="sample_time" value="{{ old('sample_time', $data["sample_time"]) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="register_mode" value="{{ $register_mode }}" >
                                <input type="hidden" name="id" id="id" value="{{ $data['id'] }}" >
                                <button type="submit" class="btn btn-primary width-100" id="btn_register">{{ $register_mode == "create" ? '新規登録' : '編集' }}</button></a>
                                <a href="{{ url()->previous() }}"><button type="button" class="btn btn-outline-secondary width-100" id="btn_cancel">キャンセル</button></a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('app_js')
    <script>
        $(function(){
            // datepickerクラス設定
            if ($.datetimepicker) {
                $.datetimepicker.setLocale('ja');
                // カレンダー表示設定(日付のみ)
                $('.datepicker').datetimepicker({
                    format:'Y-m-d',
                    timepicker:false,
                    autoclose:true,
                });
            }
        });
    </script>
@endsection
