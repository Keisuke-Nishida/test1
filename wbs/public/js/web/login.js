$(function () {
    toggleDisabled();
    displayModalAgree();
});

/**
 * ボタン押下したときに利用規約モーダル表示
 */
const displayModalAgree = function () {
    $("#agree_login").on("click", function () {
        $("#modalAgree").modal("show");
    });
};

/**
 * 利用規約に同意するのチェックボックスに
 * チェックを入れたときに利用規約に同意して登録するの
 * ボタンを実行可能にする
 */
const toggleDisabled = function () {
    // 初期化
    let isChecked = $(".modal_agree")
        .find('input[type="checkbox"]')
        .prop("checked");

    $("#check_agree").on("change", function () {
        isChecked = $(".modal_agree")
            .find('input[type="checkbox"]')
            .prop("checked");
        if (isChecked) {
            $("#button_agree").prop("disabled", false);
        } else {
            $("#button_agree").prop("disabled", true);
        }
    });
};
