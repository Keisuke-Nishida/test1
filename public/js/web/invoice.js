// 請求管理

// 請求管理画面
$(function () {
    loadInvoiceList();

    //   検索ボタン押下時にローディングスピナー表示用の処理
    $(document).on("click", "#searchButton", function () {
        displayLoading();
        setTimeout(function () {
            hideLoading();
        }, 500);
    });
});

// 請求詳細画面
$(function () {
    loadInvoiceDetaliList();
});

// 一覧のチェックボックスの操作
$(function () {
    // check_countが0のときはモーダル表示のボタンは動作しない
    let check_count = 0;
    // サーバーにデータ送信用のinput定義
    let input = $("<input>", {
        type: "hidden",
        name: "id[]",
        class: "checked_id",
    });

    // 全選択
    $(document).on("click", "#checkAllArea", function () {
        let check = $(this).find('input[type="checkbox"]').prop("checked");
        if (check) {
            $(this).find("input").prop("checked", false);
            $(".checkArea").each(function () {
                $(this).find("input").prop("checked", false);
                removeInput(this);
            });
            check_count = 0;
        } else {
            dataReset();
            $(this).find("input").prop("checked", true);
            $(".checkArea").each(function () {
                removeInput(this);
                $(this).find("input").prop("checked", true);
                addInput(this);
                check_count++;
            });
        }
        toggleDisabled(check_count);
        return false;
    });

    // 個別選択
    $(document).on("click", ".checkArea", function () {
        let check = $(this).find('input[type="checkbox"]').prop("checked");
        if (check) {
            $("#checkAllArea").find("input").prop("checked", false);
            $(this).find("input").prop("checked", false);
            removeInput(this);
            check_count--;
        } else {
            $(this).find("input").prop("checked", true);
            addInput(this);
            check_count++;
            // 行数と個別チェックの数が同数なら全選択のチェックボックスにチェックを付ける
            if (countRow() == check_count) {
                $("#checkAllArea").find("input").prop("checked", true);
            }
        }
        toggleDisabled(check_count);
        return false;
    });

    // 印刷モーダルの表示
    $(document).on("click", "#button__print", function () {
        // checkが1つでもないとボタンを押下してもモーダルは表示されない
        if (check_count > 0) {
            // 確認メッセージ
            $("#confirm_modal_select")
                .find(".modal-body")
                .children("strong")
                .text(
                    "選択された " +
                        check_count +
                        " 件のデータを印刷します。よろしいですか？"
                );
            $("#confirm_modal_select").modal("show");
        }
        return false;
    });

    // CSV出力モーダル
    $(document).on("click", "#button__csv", function () {
        // checkが1つでもないとボタンを押下してもモーダルは表示されない
        if (check_count > 0) {
            // 確認メッセージ
            $("#confirm_modal_select")
                .find(".modal-body")
                .children("strong")
                .text(
                    "選択された " +
                        check_count +
                        " 件のデータをCSV出力します。よろしいですか？"
                );
            $("#confirm_modal_select").modal("show");
        }
        return false;
    });

    // 削除確認モーダルOKクリック
    $("#btn_remove_select").on("click", function () {
        $("#remove_form").submit();
    });

    // 各ボタン押下時と一覧のページ遷移時のデータリセット
    $(document).on(
        "click",
        "#btn-csv,#btn-csv_export,#btn_search,#btn_search_clear,.page-link",
        function () {
            let check = $("#checkAllArea")
                .find('input[type="checkbox"]')
                .prop("checked");
            // 全選択チェックボックスを操作してすべてのチェックを外す
            if (check) {
                $("#checkAllArea").trigger("click");
            } else {
                $("#checkAllArea").trigger("click");
                $("#checkAllArea").trigger("click");
            }
            dataReset();
            return false;
        }
    );

    // 表示件数変更時のデータリセット
    $(document).on("change", 'select[name="main_list_length"]', function () {
        dataReset();
        return false;
    });

    /**
     * 送信データの生成
     */
    function addInput(e) {
        input.attr("id", "remove_id_" + $(e).find("input").attr("data-id"));
        input.val($(e).find("input").attr("data-id"));
        input.clone().appendTo("#remove_form");
    }

    /**
     * 送信データの削除
     */
    function removeInput(e) {
        $("#remove_form")
            .children(
                'input[id="remove_id_' +
                    $(e).find("input").attr("data-id") +
                    '"]'
            )
            .remove();
    }

    /**
     * 行数カウント
     */
    function countRow() {
        let count = 0;
        $(".checkArea").each(function () {
            count++;
        });
        return count;
    }

    /**
     * 選択削除ボタンのdisabledの切り替え
     * @param {*} check_count
     */
    function toggleDisabled(check_count) {
        if (check_count == 0) {
            $("#btn_ckbox_delete").addClass("disabled");
            $("#btn_ckbox_delete").removeClass("cursor-pointer");
        } else {
            $("#btn_ckbox_delete").removeClass("disabled");
            $("#btn_ckbox_delete").addClass("cursor-pointer");
        }
    }

    /**
     * データリセット
     */
    function dataReset() {
        check_count = 0;
        toggleDisabled(check_count);
        $("#remove_form").find("input.checked_id").remove();
        $("#checkAllArea").find("input").prop("checked", false);
    }
});

// 請求管理の一覧表示処理
function loadInvoiceList() {
    // ローディング画面の表示
    displayLoading();

    const ListTableData = setInvoiceListTableData();

    let html_invoice = "";
    let N;
    let maxRowLength = 10;
    $.each(ListTableData, function (i, e) {
        // 偶数行、奇数行分け
        if (i % 2) {
            N = "even";
        } else {
            N = "odd";
        }

        html_invoice +=
            '<tr role="row" class="' +
            N +
            '">' +
            '<td class="fixed-column--left checkArea cursor-pointer">' +
            '<div class="custom-control custom-checkbox">' +
            '<input type="checkbox" class="custom-control-input" id="base_check_' +
            e.id +
            '">' +
            '<label class="custom-control-label cursor-pointer" for="base_check_' +
            e.id +
            '"></label>' +
            "</div>" +
            "</td>" +
            '<td class="shipment_name fixed-column--left">' +
            '<button type="button" class="button__modal-link btn btn-link" data-toggle="modal" data-target="#detailModal">' +
            e.shipment_name +
            "</button>" +
            "</td>" +
            '<td class="processing_date">' +
            e.processing_date +
            "</td>" +
            '<td class="sales_number">' +
            e.sales_number +
            "</td>" +
            '<td class="order_confirmed_date">' +
            e.order_confirmed_date +
            "</td>" +
            '<td class="order_number">' +
            e.order_number +
            "</td>" +
            '<td class="shipment_date">' +
            e.shipment_date +
            "</td>" +
            '<td class="instruction_number">' +
            e.instruction_number +
            "</td>" +
            '<td class="voucher_category">' +
            e.voucher_category +
            "</td>" +
            '<td class="reservation_category">' +
            e.reservation_category +
            "</td>" +
            '<td class="main-list__operation-colmun fixed-column--right">' +
            '<a class="btn button__main button__detail" href="./detail.blade.html" role="button">詳細</a>' +
            "</td>" +
            "</tr>";

        // 最大行表示
        if (i == maxRowLength - 1) {
            return false;
        }
    });

    $("#main-list__invoice__tbody").append(html_invoice);

    // ローディング画面の非表示
    hideLoading();
}

// 請求詳細の一覧表示処理
function loadInvoiceDetaliList() {
    const DetailTableData = setInvoiceDetailTableData();

    let html_invoice_detail = "";
    let N;
    $.each(DetailTableData, function (i, e) {
        // 偶数行、奇数行分け
        if (i % 2) {
            N = "even";
        } else {
            N = "odd";
        }

        html_invoice_detail +=
            '<tr role="row" class="' +
            N +
            '">' +
            '<td class="number fixed-column--left">' +
            e.id +
            "</td>" +
            '<td class="item_name fixed-column--left">' +
            e.item_name +
            "</td>" +
            '<td class="item_code">' +
            e.item_code +
            "</td>" +
            '<td class="packaging_unit_code">' +
            e.packaging_unit_code +
            "</td>" +
            '<td class="shipments">' +
            e.shipments.toLocaleString() +
            "</td>" +
            '<td class="sales_price_per_unit">&yen;' +
            e.sales_price_per_unit.toLocaleString() +
            "</td>" +
            '<td class="unit_price_after_discount">&yen;' +
            e.unit_price_after_discount.toLocaleString() +
            "</td>" +
            '<td class="sales_amount">&yen;' +
            e.sales_amount.toLocaleString() +
            "</td>" +
            "</tr>";
    });

    $("#main-list__detail__tbody").append(html_invoice_detail);
}
