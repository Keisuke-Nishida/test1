@extends('admin.layouts.app')

@section('app_title')
{{ Util::langtext('SIDEBAR_SUB_LI_001') . ' - ' . $action }}
@endsection

@section('app_bread')
{{ Util::langtext('SIDEBAR_SUB_LI_001') }}
@endsection

@section('app_contents')
<div class="card">
    <div class="card-header">{{ $action }}</div>
    <div class="card-body">
        <form class="form" id="customer-destination-form" action="{{ route('admin/customer/destination/save') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="register_mode" value="{{ $register_mode }}" />
            <input type="hidden" name="customer_id" id="customer-id" value="{{ $customer_id }}" />
            @if (isset($data['id']))
            <input type="hidden" name="id" value="{{ $data['id'] }}" />
            @endif
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUST_DEST_L_016') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="customer-id" tabindex="1" value="{{ $customer_id }}" readonly disabled />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUST_DEST_L_018') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="code" id="code" tabindex="3" value="{{ isset($data['code']) ? $data['code'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'code'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUST_DEST_L_020') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="name_kana" id="name-kana" tabindex="5" value="{{ isset($data['name_kana']) ? $data['name_kana'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'name_kana'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUST_DEST_L_022') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="post_no" id="post-no" tabindex="7" value="{{ isset($data['post_no']) ? $data['post_no'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'post_no'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUST_DEST_L_024') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="address_2" id="address-2" tabindex="9" value="{{ isset($data['address_2']) ? $data['address_2'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'address_2'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUST_DEST_L_026') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="fax" id="fax" tabindex="11" value="{{ isset($data['fax']) ? $data['fax'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'fax'])
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUST_DEST_L_017') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="customer-name" tabindex="2" value="{{ $customer_name }}" readonly disabled />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUST_DEST_L_019') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="name" id="name" tabindex="4" value="{{ isset($data['name']) ? $data['name'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'name'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUST_DEST_L_021') }}</label>
                        <div class="col-md-9">
                            <select class="form-control" name="prefecture_id" tabindex="6" id="prefecture-id">
                                <option value=""></option>
                                @foreach ($prefectures as $prefecture)
                                @if (isset($data['prefecture_id']))
                                <option value="{{ $prefecture['id'] }}"<?php echo ($data['prefecture_id'] == $prefecture['id']) ? ' selected' : ''; ?>>{{ $prefecture['name'] }}</option>
                                @else
                                <option value="{{ $prefecture['id'] }}">{{ $prefecture['name'] }}</option>
                                @endif
                                @endforeach
                            </select>
                            @include('admin.layouts.components.error_message', ['title' => 'prefecture_id'])
                            @include('admin.layouts.components.error_message', ['title' => ''])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUST_DEST_L_023') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="address_1" id="address-1" tabindex="8" value="{{ isset($data['address_1']) ? $data['address_1'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'address_1'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUST_DEST_L_025') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="tel" id="tel" tabindex="10" value="{{ isset($data['tel']) ? $data['tel'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'tel'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUST_DEST_L_027') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="kiduke_kanji" id="kiduke-kanji" tabindex="12" value="{{ isset($data['kiduke_kanji']) ? $data['kiduke_kanji'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'kiduke_kanji'])
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary width-100" tabindex="21" type="submit">{{ Util::langtext('CUST_DEST_B_003') }}</button>
                    <a class="btn btn-outline-secondary width-100" id="customer-destination-form-cancel" data-url="{{ '/admin/customer/' . $customer_id . '/destination' }}" tabindex="22" href="#">{{ Util::langtext('CUST_DEST_B_004') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>
@include('admin.layouts.components.modal.confirm', [
    'title' => Util::langtext('SIDEBAR_SUB_LI_001'),
    'button_name' => 'OK',
    'message' => \App\Lib\Message::getMessage(\App\Lib\Message::INFO_005, [])
])
@endsection
