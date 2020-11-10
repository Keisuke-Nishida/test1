@extends('admin.layouts.no_login')

@section('app_title')
    500 Internal Server Error
@endsection

@section('app_contents')
    <div class="col-md-12">
        <div class="card-group">
            <div class="card p-4 login-body">
                <div class="card-body">
                    <h1>500 Internal Server Error</h1>
                    <p>{{ $message }}</p>
                    <p style="text-align:center">
                        <a href="/admin">トップに戻る</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
