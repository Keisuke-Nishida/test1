var url_base = "";
var table = null;
var button = "";
var delete_type = "";
var user_id = "";
var selected_rows = "";
var selected_rows_count = "";
var request_url = "";
var status = "";
var errors = [];

function exists(item, arr)
{
    if (typeof arr === "object") {
        for (let key in arr) {
            if (arr[key] == item) {
                return true;
            }
        }

        return false;
    } else if ($.isArray(arr)) {
        for (i = 0; i < arr.length; i++) {
            if (arr[i] == item) {
                return true;
            }
        }

        return false;
    }
}

function is_int(num)
{
    return Math.floor(num) == num && $.isNumeric(num);
}

function is_string_empty(str)
{
    return (str === "" || str === null) ? true : false;
}

function toggle_search_cont()
{
    $(".detailed-search-cont").slideToggle("slow", function() {
        if ($(".detailed-search-cont").is(":hidden")) {
            $("#search-toggle-icon").removeClass("fa-minus");
            $("#search-toggle-icon").addClass("fa-plus");
        } else {
            $("#search-toggle-icon").addClass("fa-minus");
            $("#search-toggle-icon").removeClass("fa-plus");
        }
    });
}

function get_list_link(type, id, link, extra_class, link_id = null)
{
    if (type == "detail") {
        return '<a href="javascript:void(0)" class="btn btn-success btn-detail ' + extra_class + '" data-toggle="tooltip" title="詳細" data-placement="top" data-id="' + id + '"><i class="fas fa-search fa-fw"></i></a>';
    }

    if (type == "edit") {
        return '<a href="' + link + '" id="'+ type +'-id-'+ link_id +'" class="btn btn-primary ' + extra_class + '" data-toggle="tooltip" title="編集" data-placement="top"><i class="fas fa-edit fa-fw"></i></a>';
    }

    if (type == "remove") {
        return '<a href="javascript:void(0)" id="'+type+'-id-'+id+'" class="btn btn-danger btn-remove ' + extra_class + '" data-toggle="tooltip" title="削除" data-placement="top" data-id="' + id + '" data-url="' + link + '"><i class="fas fa-trash-alt fa-fw"></i></a>';
    }
}