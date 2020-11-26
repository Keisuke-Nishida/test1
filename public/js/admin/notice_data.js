function search_main_list()
{
    $.ajaxSetup({
        "headers": {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    if (table !== null) {
        $("#notice_data-table").DataTable().destroy();
    }

    table = $("#notice_data-table").DataTable({
        "processing": true,
        "stateSave": true,
        "responsive": true,
        "paginate": true,
        "searching": true,
        "ajax": {
            "type": "post",
            "dataType": "json",
            "url": "/admin/notice_data/search",
            "data": {
                "name": $("#search-name").val(),
                "title": $("#search-title").val(),
                "body": $("#search-body").val(),
            },
            "timeout": 10000,
            "error": function (xhr, error, code) {
                alert("データが正常に取得できませんでした");
            }
        },
        "initComplete": function() {
            $(this).css("width", "100%");
            $(this).find('[data-toggle="tooltip"]').tooltip();
        },
        "lengthMenu": [25, 50, 100],
        "language": {
            "emptyTable": DATA_TABLE_EMPTY_TEXT,
            "info": DATA_TABLE_INFO_TEXT,
            "infoEmpty": DATA_TABLE_INFO_EMPTY_TEXT,
            "lengthMenu": DATA_TABLE_LENGTH_TEXT,
            "paginate": {
                "first": DATA_TABLE_PAGINATE_FIRST,
                "previous": DATA_TABLE_PAGINATE_PREVIOUS,
                "next": DATA_TABLE_PAGINATE_NEXT,
                "last": DATA_TABLE_PAGINATE_LAST
            },
        },
        "scrollY": 500,
        "dom": "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'<'d-flex justify-content-center'p>><'col-sm-12 col-md-4 text-right'i>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row'<'col-sm-12 col-md-4 offset-md-4'<'d-flex justify-content-center'p>>>",
        "select": {
            "style": "multi",
            "selector": "td:first-child",
            "info": false
        },
        "columnDefs": [{
            "orderable": false,
            "className": "select-checkbox",
            "targets": 0,
            "visible": true
        },
        {
            "orderable": false,
            "className": "link-cell",
            "targets": [2, 7],
            "visible": true
        }],
        "columns": [
            {
                "data": null,
                "orderable": false,
                "render": function(row, type, value) {
                    return "";
                }
            },
            { "data": "name" },
            {
                "data": null,
                "orderable": false,
                "render": function(data, type, row) {
                    return get_list_link("edit", 0, "/admin/notice_data/edit/" + row["id"], "list-button");
                }
            },
            { "data": "title" },
            { "data": "body" },
            { "data": "start_time" },
            { "data": "end_time" },
            {
                "data": null,
                "orderable": false,
                "render": function(data, type, row) {
                    return get_list_link("remove", row["id"], "/admin/notice_data/delete_single", "list-button");
                }
            }
        ],
    });
}

function clear_search_fields()
{
    $("#search-name").val("");
    $("#search-title").val("");
    $("#search-body").val("");
}

$(document).ready(function() {
    search_main_list();

    $("#search-toggle-button").click(function() {
        toggle_search_cont();
    });

    $("#notice_data-detailed-search-submit").click(function() {
        $("#notice_data-table").DataTable().destroy();
        search_main_list();
    });

    $("#notice_data-detailed-search-reset").click(function() {
        clear_search_fields();
        $("#notice_data-table").DataTable().destroy();
        search_main_list();
    });

    $("#notice_data-select-all").click(function() {
        if ($("#notice_data-select-all").is(":checked")) {
            table.rows({ page: "current" }).select();
        } else {
            table.rows({ page: "current" }).deselect();
        }
    });

    $(document).on("click", ".btn-remove", function() {
        $("#confirm_url").val($(this).data("url"));
        $("#confirm_id").val($(this).data("id"));
        $("#confirm_modal").modal("show");
    });

    $("#notice_data-multiple-delete-button").click(function(e) {
        $("#confirm_url").val($(this).data("url"));

        selected_rows = table.rows({ selected: true }).data();

        if (selected_rows.length == 0) {
            alert(NO_DATA_SELECTED);
            e.preventDefault();
            return false;
        }

        selected_rows = selected_rows.map(a => a.id);
        selected_rows = selected_rows.join(",");

        $("#confirm_id").val(selected_rows);
        $("#confirm_modal").modal("show");
    });

    $("#confirm_button").click(function() {
        $("#confirm_modal").modal("hide");

        if ($("#confirm_id").val()) {
            $.ajaxSetup({
                "headers": {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });

            $.ajax({
                "url": $("#confirm_url").val(),
                "type": "POST",
                "data": {
                    "id": $("#confirm_id").val()
                }
            }).done(function(response){
                if (response.status == 1) {
                    $("#result_info_message").html(response.message);
                    $("#result_info_modal").modal("show");
                    clear_search_fields();
                    $("#notice_data-table").DataTable().destroy();
                    search_main_list();
                } else {
                    $("#result_error_message").html(response.message);
                    $("#result_error_modal").modal("show");
                }
            });
        } else {
            document.location.href = $("#confirm_url").val();
        }
    });

    $("#confirm_modal").on("hidden.bs.modal", function() {
        $("#confirm_url").val("");
        $("#confirm_id").val("");
    });


    $("#notice_data-form-cancel").click(function(e) {
        $("#confirm_url").val($(this).data("url"));
        $("#confirm_modal").modal("show");
    });
});
