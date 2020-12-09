function search_main_list()
{
    $.ajaxSetup({
        "headers": {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    if (table !== null) {
        $("#shipment-table").DataTable().destroy();
    }

    table = $("#shipment-table").DataTable({
        "processing": true,
        "stateSave": true,
        "responsive": true,
        "paginate": true,
        "searching": true,
        "ajax": {
            "type": "post",
            "dataType": "json",
            "url": "/admin/shipment/search",
            "data": {
                "date_create_start": $("#search-date-create-start").val(),
                "date_create_end": $("#search-date-create-end").val(),
                "condition": $("#search-condition").val(),
                "customer_code": $("#search-customer-code").val(),
                "sale_name": $("#search-sale-name").val(),
                "destination_name": $("#search-destination-name").val(),
                "transport_type": $("#search-transport-type").val(),
                "order_no": $("#search-order-no").val(),
                "order_date_start": $("#search-order-date-start").val(),
                "order_date_end": $("#search-order-date-end").val(),
                "shipment_date_start": $("#search-shipment-date-start").val(),
                "shipment_date_end": $("#search-shipment-date-end").val(),
                "delivery_date_start": $("#search-delivery-date-start").val(),
                "delivery_date_end": $("#search-delivery-date-end").val(),
                "shipping_no": $("#search-order-no").val(),
                "voucher_type": $("#search-voucher-type").val()
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
        "columnDefs": [
        {
            "orderable": false,
            "className": "link-cell",
            "targets": [4],
            "visible": true
        }],
        "columns": [
            { "data": "data_create_date" },
            { "data": "condition_name" },
            { "data": "customer_code" },
            { "data": "sale_name" },
            {
                "data": null,
                "orderable": false,
                "render": function(data, type, row) {
                    return get_list_link("detail", row["id"], "/admin/shipment/detail/" + row["id"], "list-button", row["id"], "detail");
                }
            },
            { "data": "destination_name" },
            { "data": "transport_name" },
            { "data": "order_no" },
            { "data": "order_date" },
            { "data": "shipment_date" },
            { "data": "delivery_date" },
            { "data": "shipping_no" },
            { "data": "voucher_type_name" },
        ],
    });
}

function clear_search_fields()
{
    $("#search-date-create-start").val("");
    $("#search-date-create-end").val("");
    $("#search-condition").prop("selectedIndex", 0);
    $("#search-customer-code").val("");
    $("#search-sale-name").val("");
    $("#search-destination-name").val("");
    $("#search-transport-type").prop("selectedIndex", 0);
    $("#search-order-no").val("");
    $("#search-order-date-start").val("");
    $("#search-order-date-end").val("");
    $("#search-shipment-date-start").val("");
    $("#search-shipment-date-end").val("");
    $("#search-delivery-date-start").val("");
    $("#search-delivery-date-end").val("");
    $("#search-shipping-no").val("");
    $("#search-voucher-type").prop("selectedIndex", 0);
}

$(document).ready(function() {
    search_main_list();

    $("#search-toggle-button").click(function() {
        toggle_search_cont();
    });

    $("#shipment-detailed-search-submit").click(function() {
        errors = [];

        if (!isStartDateValid($("#search-date-create-start").val(), $("#search-date-create-end").val())) {
            errors['date_create'] = 0;
            $("#search-date-create-start").addClass("error-field-border");
            $("#search-date-create-end").addClass("error-field-border");
            $("#search-date-create-start").removeClass("field-border");
            $("#search-date-create-end").removeClass("field-border");
        } else {
            errors['date_create'] = 1;
            $("#search-date-create-start").removeClass("error-field-border");
            $("#search-date-create-end").removeClass("error-field-border");
            $("#search-date-create-start").addClass("field-border");
            $("#search-date-create-end").addClass("field-border");
        }

        if (!isStartDateValid($("#search-order-date-start").val(), $("#search-order-date-end").val())) {
            errors['order_date'] = 0;
            $("#search-order-date-start").addClass("error-field-border");
            $("#search-order-date-end").addClass("error-field-border");
            $("#search-order-date-start").removeClass("field-border");
            $("#search-order-date-end").removeClass("field-border");
        } else {
            errors['order_date'] = 1;
            $("#search-order-date-start").removeClass("error-field-border");
            $("#search-order-date-end").removeClass("error-field-border");
            $("#search-order-date-start").addClass("field-border");
            $("#search-order-date-end").addClass("field-border");
        }

        if (!isStartDateValid($("#search-shipment-date-start").val(), $("#search-shipment-date-end").val())) {
            errors['shipment_date'] = 0;
            $("#search-shipment-date-start").addClass("error-field-border");
            $("#search-shipment-date-end").addClass("error-field-border");
            $("#search-shipment-date-start").removeClass("field-border");
            $("#search-shipment-date-end").removeClass("field-border");
        } else {
            errors['shipment_date'] = 1;
            $("#search-shipment-date-start").removeClass("error-field-border");
            $("#search-shipment-date-end").removeClass("error-field-border");
            $("#search-shipment-date-start").addClass("field-border");
            $("#search-shipment-date-end").addClass("field-border");
        }

        if (!isStartDateValid($("#search-delivery-date-start").val(), $("#search-delivery-date-end").val())) {
            errors['delivery_date'] = 0;
            $("#search-delivery-date-start").addClass("error-field-border");
            $("#search-delivery-date-end").addClass("error-field-border");
            $("#search-delivery-date-start").removeClass("field-border");
            $("#search-delivery-date-end").removeClass("field-border");
        } else {
            errors['delivery_date'] = 1;
            $("#search-delivery-date-start").removeClass("error-field-border");
            $("#search-delivery-date-end").removeClass("error-field-border");
            $("#search-delivery-date-start").addClass("field-border");
            $("#search-delivery-date-end").addClass("field-border");
        }

        if (exists(0, errors)) {
            return false;
        } else {
            $("#shipment-table").DataTable().destroy();
            search_main_list();
        }
    });

    $("#shipment-detailed-search-reset").click(function() {
        clear_search_fields();
        $("#user-table").DataTable().destroy();
        search_main_list();
    });

    $("#search-date-create-start").datetimepicker({
        format: "Y/m/d",
        onShow: function (ct) {},
        timepicker: false,
    });

    $("#search-date-create-end").datetimepicker({
        format: "Y/m/d",
        onShow: function (ct) {},
        timepicker: false,
    });

    $("#search-order-date-start").datetimepicker({
        format: "Y/m/d",
        onShow: function (ct) {},
        timepicker: false,
    });

    $("#search-order-date-end").datetimepicker({
        format: "Y/m/d",
        onShow: function (ct) {},
        timepicker: false,
    });

    $("#search-shipment-date-start").datetimepicker({
        format: "Y/m/d",
        onShow: function (ct) {},
        timepicker: false,
    });

    $("#search-shipment-date-end").datetimepicker({
        format: "Y/m/d",
        onShow: function (ct) {},
        timepicker: false,
    });

    $("#search-delivery-date-start").datetimepicker({
        format: "Y/m/d",
        onShow: function (ct) {},
        timepicker: false,
    });

    $("#search-delivery-date-end").datetimepicker({
        format: "Y/m/d",
        onShow: function (ct) {},
        timepicker: false,
    });

    $("#confirm_button").click(function() {
        $("#confirm_modal").modal("hide");
        document.location.href = $("#confirm_url").val();
    });

    $("#confirm_modal").on("hidden.bs.modal", function() {
        $("#confirm_url").val("");
        $("#confirm_id").val("");
    });

    $("#shipment-detail-cancel").click(function(e) {
        $("#confirm_url").val($(this).data("url"));
        $("#confirm_modal").modal("show");
    });

    $("#shipment-detail-print").click(function(e) {
        e.preventDefault();
        window.open("/admin/shipment/detail/" + $("#shipment-id").val() + "/print", "_blank", "width=700");
    });
});
