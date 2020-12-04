@extends('admin.layouts.app')

@section('app_title')
    サンプル
@endsection

@section('app_bread')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">サンプル</li>
    </ol>
@endsection

@section('app_contents')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3 d-flex">
                        <div class="col-lg-2">
                            <input type="text" class="form-control search-text" placeholder="名前" name="name" id="name">
                        </div>
                        <div class="col-lg-4">
                            <button type="button" class="btn btn-primary width-100 mr-2" id="btn_search">検索</button>
                            <button type="button" class="btn btn-outline-info width-100" id="btn_search_clear">クリア</button>
                        </div>
                    </div>
                    <hr>
                    <table class="table table-striped table-bordered datatable table-sm" id="main_list">
                        <thead>
                        <tr role="row">
                            <th>ID</th>
                            <th>名前</th>
                            <th>サンプル１</th>
                            <th>サンプル２</th>
                            <th>サンプル３</th>
                            <th>サンプル時刻</th>
                            <th>更新者</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.modal.message', ['title' => 'サンプル'])
    @include('admin.layouts.components.modal.result_info', ['title' => 'サンプル'])
    @include('admin.layouts.components.modal.result_error', ['title' => 'サンプル'])
    @include('admin.layouts.components.modal.message', ['title' => 'サンプル'])
    @include('admin.layouts.components.modal.confirm', [
        'title'         => 'サンプル削除確認',
        'button_name'   => '削除',
        'message'       => \App\Lib\Message::getMessage(\App\Lib\Message::INFO_004, ['サンプル'])
    ])

@endsection

@section('app_js')
    <script>
        $(function(){

            // ※サンプルなので直接記述していますが、共通化する方が良いかと思います


            // 初回検索
            search_main_list();

            // 検索ボタン
            $('#btn_search').on('click', function(){
                $('#main_list').DataTable().destroy();
                search_main_list();
            });
            // クリアボタン
            $('#btn_search_clear').on('click', function(){
                $('.search-text').val("");
                $('#main_list').DataTable().destroy();
                search_main_list();
            });

            // 削除ボタン
            $(document).on('click', '.btn-remove', function(){
                $('#confirm_url').val($(this).data('url') + $(this).data('id'));
                $('#confirm_modal').modal('show');
            });

            // 確認の削除ボタン
            $('#confirm_button').on('click', function() {

                $('#confirm_modal').modal('hide');

                $.ajax({url: $('#confirm_url').val()})
                .done(function(response){

                    if (response.status == 1) {
                        $('#result_info_message').html(response.message);
                        $('#result_info_modal').modal('show');
                    } else {
                        $('#result_error_message').html(response.message);
                        $('#result_error_modal').modal('show');
                    }
                });
            });

            function search_main_list() {
                // // DataTables設定
                let table = $('#main_list').dataTable({
                    "processing": true,
                    "stateSave":  true,
                    "responsive": true,
                    "paginate":   true,
                    "ajax": {
                        type: "post",
                        dataType: 'json',
                        url: '/admin/sample/list/search',
                        data: {
                            'name': $('#name').val()
                        },
                        timeout: 10000,
                        error: function (xhr, error, code) {
                            alert('データが正常に取得できませんでした');
                        }
                    },
                    // 読み込み完了後イベント
                    "initComplete": function( ) {
                        // (レスポンシブが利かなくなるので、再定義)
                        $(this).css('width', '100%');
                        // ツールチップ設定
                        $(this).find('[data-toggle="tooltip"]').tooltip();
                    },
                    "bFilter":    false,
                    "columns": [
                        {data: 'id'},
                        {data: 'name'},
                        {data: 'sample1'},
                        {data: 'sample2'},
                        {data: 'sample3'},
                        {data: 'sample_time'},
                        {data: 'update_user.name'},
                        // 各操作列
                        {
                            data: function (p) {
                                // 詳細
                                return getListLink('edit', 0, '/admin/sample/edit/'+p.id, 'list-button') + " " +
                                    getListLink('remove', p.id, '/admin/sample/delete/', 'list-button');
                            }
                        }

                    ],
                    "columnDefs": [{
                        targets: [7], orderable: false, className: 'text-center', width: '110px'
                    }],
                });
            }

            /**
             * 一覧操作列リンク作成
             * @param type
             * @param id
             * @param link
             * @returns {string}
             */
            function getListLink(type, id, link, clazz) {
                if (type == "detail") {
                    return '<a href="javascript:void(0)" class="btn btn-success btn-detail '+clazz+'" data-toggle="tooltip" title="詳細" data-placement="top" data-id="'+id+'"><i class="fas fa-search fa-fw"></i></a>';
                }
                if (type == "edit") {
                    return '<a href="'+link+'" class="btn btn-primary '+clazz+'" data-toggle="tooltip" title="編集" data-placement="top"><i class="fas fa-edit fa-fw"></i></a>';
                }
                if (type == "remove") {
                    return '<a href="javascript:void(0)" class="btn btn-danger btn-remove '+clazz+'" data-toggle="tooltip" title="削除" data-placement="top" data-id="'+id+'" data-url="'+link+'"><i class="fas fa-trash-alt fa-fw"></i></a>';
                }
            }
        });
    </script>
@endsection
