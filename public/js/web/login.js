$(function () {
    initBeforeLogin();
});

const initBeforeLogin = function () {
    beforeLogin();

    toggleDisabled();
};

const CSRF_TOKEN = $("input[name='_token']").val();

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

/**
 * ログイン前の処理
 * ユーザーの免責事項同意履歴などの判断を行う
 */
const beforeLogin = function () {
    $("#login").on("click", function () {
        const login_id = $("#login_id").val();
        const password = $("#password").val();
        const remember = $("#remember").val();

        $.ajax({
            type: "POST",
            url: "/api/beforeLogin",
            data: {
                _token: CSRF_TOKEN,
                login_id: login_id,
                password: password,
                remember: remember,
            },
            dataType: "json",
        })
            .done(function (response) {
                // エラーメッセージの初期化
                $(document).find(".errors").remove();
                // モーダル表示時
                if (!response.login) {
                    // モーダルの表示
                    displayModalAgree(response);

                    // モーダルが非表示になった場合、リロードして蓄積されたデータを削除する
                    $("#modalAgree").on("hidden.bs.modal", function (e) {
                        location.reload();
                    });

                    // モーダルの入力に対するバリデーション
                    validateModalForm(response.status);
                }

                // 通常ログイン時
                if (response.login) {
                    // 通常のログインを行う
                    $("#form-login").attr("action", "/login");
                    $("#form-login").submit();
                }
            })
            .fail(function (XMLHttpRequest, textStatus, errorThrown) {
                // エラーメッセージの初期化
                $(document).find(".errors").remove();

                // エラー内容の表示
                let response = XMLHttpRequest.responseJSON;
                displayErrorMessage(response, errorThrown);
            });
    });
};

/**
 * 利用規約モーダルのバリデーション
 * @param {string} status
 */
const validateModalForm = function (status) {
    $("#button_agree").on("click", function () {
        const login_id = $("#login_id").val();
        const password = $("#password").val();
        const email = $("#email").val();
        const checkAgree = $("input[name='check_agree']:checked").val();

        $.ajax({
            type: "POST",
            url: "/api/validateModalForm",
            data: {
                _token: CSRF_TOKEN,
                login_id: login_id,
                password: password,
                email: email,
                check_agree: checkAgree,
                status: status,
            },
            dataType: "json",
        })
            .done(function (response) {
                let status = response.status;

                // エラーメッセージの初期化
                $(document).find(".errors").remove();

                if (status == "first_login") {
                    // メールの送信
                    // TODO:後ほど実装
                    // sendRegistrationMail(response.user_id);
                    // result画面へ遷移
                    $("#form_agree").attr("action", "/api/resultEmail");
                    $("#form_agree").submit();
                }

                if (status == "update_agreement") {
                    // ユーザーの利用規約同意データの更新
                    saveUserAgreeData(response.user_id);
                }
            })
            .fail(function (XMLHttpRequest, textStatus, errorThrown) {
                // エラーメッセージの初期化
                $(document).find(".errors").remove();

                // エラー内容の表示
                let response = XMLHttpRequest.responseJSON;
                displayErrorMessage(response, errorThrown);
            });
    });
};

/**
 * user_agree_dataテーブルのagree_timeの更新
 * @param {number} user_id
 */
const saveUserAgreeData = function (user_id) {
    $.ajax({
        type: "POST",
        url: "/api/saveUserAgreeData",
        data: {
            _token: CSRF_TOKEN,
            id: user_id,
        },
        dataType: "json",
    })
        .done(function (response) {
            // エラーメッセージの初期化
            $(document).find(".errors").remove();

            // 通常のログインを行う
            if (response.status == 1) {
                $("#form-login").attr("action", "/login");
                $("#form-login").submit();
            }

            // サーバー側エラー
            if (response.status !== 1) {
                alert(response.message);
            }
        })
        .fail(function (XMLHttpRequest, textStatus, errorThrown) {
            // エラーメッセージの初期化
            $(document).find(".errors").remove();

            // エラー内容の表示
            let response = XMLHttpRequest.responseJSON;
            displayErrorMessage(response, errorThrown);
        });
};

/**
 * 本登録用メールの送信
 */
const sendRegistrationMail = function (user_id) {
    // TODO: 後ほど実装
};

/**
 * バリデーションエラー表示用のdiv要素を設定する
 */
const setErrorDiv = function () {
    return $("<div></div>", {
        class: "errors text-danger mb-2",
    });
};

/**
 * バリデーションエラーの表示
 * @param {array} response
 * @param {*} errorThrown
 */
const displayErrorMessage = function (response, errorThrown) {
    if (response == undefined || !("errors" in response)) {
        alert(errorThrown);
        return;
    }

    $.each(response.errors, function (index, error) {
        let errorMessage = setErrorDiv();
        errorMessage.append(error);
        $("#" + index)
            .parent()
            .after(errorMessage);
    });
};

/**
 * ボタン押下したときに利用規約モーダル表示
 * @param {array} response
 */
const displayModalAgree = function (response) {
    let status = response.status;
    let message = response.message;

    // 最新の利用規約の内容を表示
    $("#privacy_body").append(message);

    // 初回ログイン時にメールアドレス入力欄がなかったら生成
    if (status == "first_login") {
        $("input[name='email']").parent().show();
    }
    // 利用規約更新時はメールアドレスの入力欄を削除
    if (status == "update_agreement") {
        $("input[name='email']").parent().hide();
    }
    $("#modalAgree").modal("show");
};
