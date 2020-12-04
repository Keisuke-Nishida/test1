function search_main_list()
{
    $.ajaxSetup({
        "headers": {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    if (table !== null) {
        $("#role-menu-table").DataTable().destroy();
    }

    table = $("#role-menu-table").DataTable({
        "processing": true,
        "stateSave": true,
        "responsive": true,
        "paginate": true,
        "searching": true,
        "ajax": {
            "type": "post",
            "dataType": "json",
            "url": "/admin/role_menu/search",
            "data": {
                "name": $("#search-name").val(),
                "type": $("#search-type").val(),
                "menu_id": $("#search-menu").val(),
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
            "orderable": true,
            "className": "link-cell",
            "targets": [1, 3],
            "visible": true
        },
        {
            "orderable": false,
            "className": "link-cell",
            "targets": [2, 4, 5],
            "visible": true
        },
        {
            "orderable": false,
            "targets": [4],
            "visible": true,
            "width": "315px"
        }],
        "columns": [
            {
                "data": null,
                "orderable": false,
                "render": function(row, type, value) {
                    return "";
                }
            },
            { "data": "role_name" },
            {
                "data": null,
                "orderable": false,
                "render": function(data, type, row) {
                    return get_list_link("edit", 0, "/admin/role_menu/edit/" + row["role_id"], "list-button", row["role_id"]);
                }
            },
            { "data": "role_type" },
            { "data": "menu_names" },
            {
                "data": null,
                "orderable": false,
                "render": function(data, type, row) {
                    return get_list_link("remove", row["role_id"], "/admin/role_menu/delete_single", "list-button");
                }
            }
        ],
    });
}

function clear_search_fields()
{
    $("#search-name").val("");
    $("#search-type").prop("selectedIndex", 0);
    $("#search-menu").prop("selectedIndex", 0);
}

$(document).ready(function() {
    search_main_list();

    $("#search-toggle-button").click(function() {
        toggle_search_cont();
    });

    $("#role-menu-detailed-search-submit").click(function() {
        $("#role-menu-table").DataTable().destroy();
        search_main_list();
    });

    $("#role-menu-detailed-search-reset").click(function() {
        clear_search_fields();
        $("#role-menu-table").DataTable().destroy();
        search_main_list();
    });

    $("#role-menu-select-all").click(function() {
        if ($("#role-menu-select-all").is(":checked")) {
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

    $("#role-menu-multiple-delete-button").click(function(e) {
        $("#confirm_url").val($(this).data("url"));

        selected_rows = table.rows({ selected: true }).data();

        if (selected_rows.length == 0) {
            alert(NO_DATA_SELECTED);
            e.preventDefault();
            return false;   
        }

        selected_rows = selected_rows.map(a => a.role_id);
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
                    "role_id": $("#confirm_id").val()
                }
            }).done(function(response){
                if (response.status == 1) {
                    $("#result_info_message").html(response.message);
                    $("#result_info_modal").modal("show");
                    clear_search_fields();                
                    $("#role-menu-table").DataTable().destroy();
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

    $("#role-menu-form-cancel").click(function(e) {
        $("#confirm_url").val($(this).data("url"));
        $("#confirm_modal").modal("show");
    });

    $("#type").change(function(e) {
        selected = $(this).val();
        json_data = $("#menu-data").val();
        json_data = $.parseJSON(json_data);

        if (!is_string_empty(selected)) {
            json_data = json_data[selected];
            $("#available-menus").empty();
            $("#selected-menus").empty();

            for (var key in json_data) {
                $("#available-menus").append(
                    $("#available-menus").append($("<option></option>").val(json_data[key].id).html(json_data[key].name))
                );
            }

            if ($("#register-mode").length && $("#register-mode").val() == 'edit') {
                if (selected == $("#selected-type").val()) {
                    json_data2 = $("#selected-menus-data").val();
                    json_data2 = $.parseJSON(json_data2);

                    for (var key2 in json_data2) {
                        $("#selected-menus").append(
                            $("#selected-menus").append($("<option></option>").val(json_data2[key2].id).html(json_data2[key2].name).prop("selected", "true"))
                        );
                    }
                }
            }
        }
    });

    $("#menu-move-right").click(function() {
        if ($("#available-menus").find("option").length) {
            $("#available-menus option:selected").remove().appendTo("#selected-menus");
        }
    });

    $("#menu-move-left").click(function() {
        if ($("#selected-menus").find("option").length) {
            $("#selected-menus option:selected").remove().appendTo("#available-menus");
            $("#available-menus").html($("#available-menus").find("option").sort(function(x, y) {
                return parseInt($(x).val()) > parseInt($(y).val()) ? 1 : -1;
            }));

            $("#selected-menus").find("option").each(function(key, value) {
                $(value).prop("selected", "true");
            });
        }
    });
});
