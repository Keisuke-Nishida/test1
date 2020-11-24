@extends('admin.layouts.app')

@section('app_title')
{{ Util::langtext('SIDEBAR_LI_003') . ' - ' . $action }}
@endsection

@section('app_bread')
{{ Util::langtext('SIDEBAR_LI_003') }}
@endsection

@section('app_contents')
<div class="card">
    <div class="card-header">{{ $action }}</div>
    <div class="card-body">
        <form class="form" id="customer-form" action="{{ route('admin/customer/save') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="register_mode" value="{{ $register_mode }}" />
            @if (isset($data['id']))
            <input type="hidden" name="id" value="{{ $data['id'] }}" />
            @endif
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_019') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="code" id="" tabindex="1" value="{{ isset($data['code']) ? $data['code'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'code'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_021') }} <span class="text-danger">&#x203B;</span></label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="name" id="name" tabindex="3" value="{{ isset($data['name']) ? $data['name'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'name'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_023') }}</label>
                        <div class="col-md-9">
                            <select class="form-control" name="prefecture_id" tabindex="5" id="prefecture-id">
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
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_025') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="address_2" id="address-2" tabindex="7" value="{{ isset($data['address_2']) ? $data['address_2'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'address_2'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_027') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="tel" id="tel" tabindex="9" value="{{ isset($data['tel']) ? $data['tel'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'tel'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_029') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="uriage_1" id="uriage-1" tabindex="11" value="{{ isset($data['uriage_1']) ? $data['uriage_1'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'uriage_1'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_031') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="uriage_3" id="uriage-3" tabindex="13" value="{{ isset($data['uriage_3']) ? $data['uriage_3'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'uriage_3'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_033') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="uriage_5" id="uriage-5" tabindex="15" value="{{ isset($data['uriage_5']) ? $data['uriage_5'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'uriage_5'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_035') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="uriage_7" id="uriage-7" tabindex="17" value="{{ isset($data['uriage_7']) ? $data['uriage_7'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'uriage_7'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_037') }}</label>
                        <div class="col-md-9">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input class="custom-control-input" type="checkbox" name="core_system_flag" id="core-system-flag" tabindex="19" value="1"{{ (isset($data['core_system_flag']) && $data['core_system_flag']) ? ' checked' : '' }} />
                                <label class="custom-control-label cursor-pointer mr-3" for="core-system-flag">&nbsp;</label>
                            </div>
                            @include('admin.layouts.components.error_message', ['title' => 'core_system_flag'])
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_020') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="name_kana" id="name-kana" tabindex="2" value="{{ isset($data['name_kana']) ? $data['name_kana'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'name_kana'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_022') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="post_no" id="post-no" tabindex="4" value="{{ isset($data['post_no']) ? $data['post_no'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'post_no'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_024') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="address_1" id="address-1" tabindex="6" value="{{ isset($data['address_1']) ? $data['address_1'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'address_1'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_026') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="kiduke_kanji" id="kiduke-kanji" tabindex="8" value="{{ isset($data['kiduke_kanji']) ? $data['kiduke_kanji'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'kiduke_kanji'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_028') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="fax" id="fax" tabindex="10" value="{{ isset($data['fax']) ? $data['fax'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'fax'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_030') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="uriage_2" id="uriage-2" tabindex="12" value="{{ isset($data['uriage_2']) ? $data['uriage_2'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'uriage_2'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_032') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="uriage_4" id="uriage-4" tabindex="14" value="{{ isset($data['uriage_4']) ? $data['uriage_4'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'uriage_4'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_034') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="uriage_6" id="uriage-6" tabindex="16" value="{{ isset($data['uriage_6']) ? $data['uriage_6'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'uriage_6'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ Util::langtext('CUSTOMER_L_036') }}</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="uriage_8" id="uriage-8" tabindex="18" value="{{ isset($data['uriage_8']) ? $data['uriage_8'] : '' }}" />
                            @include('admin.layouts.components.error_message', ['title' => 'uriage_8'])
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary width-100" tabindex="21" type="submit">{{ Util::langtext('CUSTOMER_B_007') }}</button>
                    <a class="btn btn-outline-secondary width-100" id="customer-form-cancel" data-url="/admin/customer/index" tabindex="22" href="#">{{ Util::langtext('CUSTOMER_B_008') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>
@include('admin.layouts.components.modal.confirm', [
    'title' => Util::langtext('SIDEBAR_LI_003'),
    'button_name' => 'OK',
    'message' => \App\Lib\Message::getMessage(\App\Lib\Message::INFO_005, [])
])
@endsection
