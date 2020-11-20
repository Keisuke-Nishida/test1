@extends('admin.layouts.app')

@section('app_title')
HOME
@endsection

@section('app_bread')
HOME
@endsection

@section('app_contents')
    <div class="row">
        <div class="col-12">
            <div class="card main-card">
                <div class="card-header">
                    メニュー
                </div>
                <div class="card-body main-menu">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3 text-center link-card shadow">
                            <a href="#">
                                <i class="far fa-file-alt fa-3x"></i>
                                <p>請求管理</p>
                                <p>{{ $invoice_count }}</p>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3 text-center link-card shadow">
                            <a href="#">
                                <i class="fas fa-truck fa-3x"></i>
                                <p>出荷管理</p>
                                <p>{{ $shipment_count }}</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
