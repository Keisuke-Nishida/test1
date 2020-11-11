// 管理一覧の行列固定
$(function () {
    fixedTableInit();

    /**
     * 行列固定テーブル 初期化
     */
    function fixedTableInit() {
        fixedTable();
    }
});

const fixedTable = function () {
    // コピー元となるベースのテーブルのデータを取得
    const baseTable = $("[data-fixed_table]");
    const baseTableClass = $(baseTable).attr("class");

    const baseTableThead = $(baseTable).find("thead");
    const baseTableTheadClass = $(baseTable).find("thead").attr("class");
    const baseTableTheadTr = baseTableThead.find("tr");
    const baseTableTheadTrTh = baseTableThead.find("th");

    const baseTableTbody = $(baseTable).find("tbody");
    const baseTableTbodyClass = baseTableTbody.attr("class");
    const baseTableTbodyTr = baseTableTbody.find("tr");

    const baseTableTbodyTrHeight = baseTableTbody.find("tr").outerHeight();

    const baseTableWrapperHeight = $(baseTable).parent().outerHeight();
    let baseTableWrapperWidth = $(baseTable).parent().innerWidth();
    $(window).resize(function () {
        baseTableWrapperWidth = $(baseTable).parent().innerWidth();
    });

    /**
     * 初期化
     */
    function init() {
        $.each(baseTable, function (i, e) {
            // １．列のコピペ
            if ($(e).find(".fixed-column--left").length > 0) {
                copyLeftColumn(e);
            }
            if ($(e).find(".fixed-column--right").length > 0) {
                copyRightColumn(e);
            }

            // Y方向のスクロール時に固定列も動かす
            $(".table-responsive").scroll(function () {
                let scrollY = $(this).scrollTop();
                $(".copy-column--left table").css("top", -scrollY);
            });

            // ２．テーブルヘッダーのコピペ
            copyHeaderRow(e);

            // X方向のスクロール時にテーブルヘッダーも動かす
            $(".table-responsive").scroll(function () {
                let scrollX = $(this).scrollLeft();
                $(".copy-header__row table").css("left", -scrollX);
            });

            // ３．１の列と２のテーブルヘッダーが重なっているところをコピペ
            if ($(e).find(".fixed-column--left").length > 0) {
                copyLeftHeaderCell(e);
            }
            if ($(e).find(".fixed-column--right").length > 0) {
                copyRightHeaderCell(e);
            }

            // ブラウザのリサイズが行われたときはテーブルヘッダーの更新を行う
            $(window).resize(function () {
                initHeader(e);
            });

            // かぶさって見えなくなっているチェックボックスの機能削除
            // コピー前にJSによるチェックボックスの機能を削除しないように最後に行う
            deleteCheckboxFeature(e);
        });
    }

    /**
     * テーブル左側の列を固定する処理
     * fixed-column--leftクラスを持っている左列をベースのテーブルの上にコピペ
     * @param {*} e
     */
    function copyLeftColumn(e) {
        let copyTable = $("<table></table>", {
            class: baseTableClass,
            "data-copy_table": "",
            "aria-hidden": "true",
        });
        let copyTableThead = $("<thead></thead>", {
            class: baseTableTheadClass,
        });
        let copyTableTbody = $("<tbody></tbody>", {
            class: baseTableTbodyClass,
        });

        // theadのfixed-column--leftクラスを持っている要素をコピー
        let fixedTheadColumnClass = ".fixed-column--left";
        copyTableThead = compositeTableThead(
            copyTableThead,
            fixedTheadColumnClass,
            true
        );

        // tbodyのfixed-column--leftクラスを持っている要素をコピー
        let fixedTbodyColumnClass = ".fixed-column--left";
        copyTableTbody = compositeTableTbody(
            copyTableTbody,
            fixedTbodyColumnClass
        );

        const compositeTable = copyTable
            .append(copyTableThead)
            .append(copyTableTbody);

        let copyTableWrapperDiv = $("<div></div>")
            .css("overflow", "hidden")
            .css("height", baseTableWrapperHeight)
            .append(compositeTable)
            .addClass("copy-column--left");

        $(e).closest(".table-responsive").append(copyTableWrapperDiv);
    }

    /**
     * テーブル右側の列を固定する処理
     * fixed-column--rightクラスを持っている右列をベースのテーブルの上にコピペ
     * @param {*} e
     */
    function copyRightColumn(e) {
        let copyTable = $("<table></table>", {
            class: baseTableClass,
            "data-copy_table": "",
            "aria-hidden": "true",
        });
        let copyTableThead = $("<thead></thead>", {
            class: baseTableTheadClass,
        });
        let copyTableTbody = $("<tbody></tbody>", {
            class: baseTableTbodyClass,
        });

        // theadのfixed-column--rightクラスを持っている要素をコピー
        let fixedTheadColumnClass = ".fixed-column--right";
        copyTableThead = compositeTableThead(
            copyTableThead,
            fixedTheadColumnClass,
            false
        );

        // tbodyのfixed-column--rightクラスを持っている要素をコピー
        let fixedTbodyColumnClass = ".fixed-column--right";
        copyTableTbody = compositeTableTbody(
            copyTableTbody,
            fixedTbodyColumnClass
        );

        const compositeTable = copyTable
            .append(copyTableThead)
            .append(copyTableTbody);

        let copyTableWrapperDiv = $("<div></div>")
            .css("overflow", "hidden")
            .css("height", baseTableWrapperHeight)
            .append(compositeTable)
            .addClass("copy-column--right");

        $(e).closest(".table-responsive").append(copyTableWrapperDiv);
    }

    /**
     * テーブルヘッダー固定用の処理
     * theadを
     * @param {*} e
     */
    function copyHeaderRow(e) {
        let copyTable = $("<table></table>", {
            class: baseTableClass,
            "data-copyTable": "",
            "aria-hidden": "true",
        });
        let copyTableThead = $("<thead></thead>", {
            class: baseTableTheadClass,
        });

        // theadをコピー
        if (baseTableThead.length > 0) {
            $.each(baseTableTheadTr, function (i, e) {
                let copyTableTheadTr = $("<tr></tr>");

                $.each(baseTableTheadTrTh, function (i, e) {
                    let thead_th_width = $(e).outerWidth();

                    // 幅指定
                    $(e).css("width", thead_th_width);
                    $(e).clone().appendTo(copyTableTheadTr);
                });

                // 最後のクラスの追加はdeleteCheckboxFeature(e)のための目印
                copyTableThead
                    .append(copyTableTheadTr)
                    .addClass("delete-checkbox-feature");
            });
        }

        const compositeTable = copyTable.append(copyTableThead);

        let copyTableWrapperDiv = $("<div></div>")
            .css("width", baseTableWrapperWidth)
            .css("overflow", "hidden")
            .append(compositeTable)
            .addClass("copy-header__row");

        $(e).closest(".table-responsive").append(copyTableWrapperDiv);
    }

    /**
     * テーブルヘッダー固定用（左端）の処理
     * @param {*} e
     */
    function copyLeftHeaderCell(e) {
        let copyTable = $("<table></table>", {
            class: baseTableClass,
            "data-copy_table": "",
            "aria-hidden": "true",
        });
        let copyTableThead = $("<thead></thead>", {
            class: baseTableTheadClass,
        });

        // theadのfixed-column--leftクラスを持っている要素をコピー
        let fixedTheadColumnClass = ".fixed-column--left";
        copyTableThead = compositeTableThead(
            copyTableThead,
            fixedTheadColumnClass,
            false
        );

        const compositeTable = copyTable.append(copyTableThead);

        let copyTableWrapperDiv = $("<div></div>")
            .append(compositeTable)
            .addClass("copy-header__cell--left");

        $(e).closest(".table-responsive").append(copyTableWrapperDiv);
    }

    /**
     * テーブルヘッダー固定用（右端）の処理
     * @param {*} e
     */
    function copyRightHeaderCell(e) {
        let copyTable = $("<table></table>", {
            class: baseTableClass,
            "data-copy_table": "",
            "aria-hidden": "true",
        });
        let copyTableThead = $("<thead></thead>", {
            class: baseTableTheadClass,
        });

        // theadのfixed-column--rightクラスを持っている要素をコピー
        let fixedTheadColumnClass = ".fixed-column--right";
        copyTableThead = compositeTableThead(
            copyTableThead,
            fixedTheadColumnClass,
            false
        );

        const compositeTable = copyTable.append(copyTableThead);

        let copyTableWrapperDiv = $("<div></div>")
            .append(compositeTable)
            .addClass("copy-header__cell--right");

        $(e).closest(".table-responsive").append(copyTableWrapperDiv);
    }

    /**
     * 各固定用処理内で使用するtableのtheadを合成する処理
     * @param {*} copyTableThead
     * @param {*} fixedTheadColumnClass
     * @param {boolean} hasDeleteCheckboxFeature
     */
    function compositeTableThead(
        copyTableThead,
        fixedTheadColumnClass,
        hasDeleteCheckboxFeature
    ) {
        if (baseTableThead.length > 0) {
            $.each(baseTableTheadTr, function (i, e) {
                let copyTableTheadTr = $("<tr></tr>");
                $(e)
                    .find(fixedTheadColumnClass)
                    .clone()
                    .appendTo(copyTableTheadTr);

                copyTableThead.append(copyTableTheadTr);

                // このクラスはdeleteCheckboxFeature(e)のための目印
                if (hasDeleteCheckboxFeature) {
                    copyTableThead.addClass("delete-checkbox-feature");
                }
            });
        }
        return copyTableThead;
    }

    /**
     * 各固定用処理内で使用するtableのtbodyを合成する処理
     * @param {*} copyTableTbody
     * @param {*} fixedTbodyColumnClass
     */
    function compositeTableTbody(copyTableTbody, fixedTbodyColumnClass) {
        if (baseTableTbody.length > 0) {
            $.each(baseTableTbodyTr, function (i, e) {
                let copyTableTbodyTr = $("<tr></tr>");
                // 高さ指定
                copyTableTbodyTr.css("height", baseTableTbodyTrHeight);
                $(e)
                    .find(fixedTbodyColumnClass)
                    .clone()
                    .appendTo(copyTableTbodyTr);
                copyTableTbody.append(copyTableTbodyTr);
            });
        }
        return copyTableTbody;
    }

    /**
     * テーブルのコピペで重なって見えなくなったチェックボックスは
     * その機能が動作しないようにJS用のidとclassを削除する
     * @param {*} e
     */
    function deleteCheckboxFeature(e) {
        // ベースのテーブルのtheadのthからid="checkAllArea"を削除
        if (baseTableThead.length > 0) {
            const baseTableCheckAllArea = baseTableThead.find("#checkAllArea");
            baseTableCheckAllArea.removeAttr("id");
        }

        // tbodyのtdからclass="checkArea"を削除
        if (baseTableTbody.length > 0) {
            const checkArea = baseTableTbody.find(".checkArea");
            $.each(checkArea, function (i, e) {
                checkArea.removeClass("checkArea");
            });
        }

        // copyLeftColumn(e)とcopyHeaderRow(e)で生成した
        // チェックボックスの機能を削除
        if ($(".delete-checkbox-feature").length > 0) {
            const copyTableCheckAllArea = $(".delete-checkbox-feature").find(
                "#checkAllArea"
            );
            copyTableCheckAllArea.removeAttr("id");
        }
    }

    /**
     * ブラウザ画面リサイズ時のテーブルヘッダーの更新
     * @param {*} e
     */
    function initHeader(e) {
        $(".copy-header__row").remove();
        $(".copy-header__cell--left").remove();
        $(".copy-header__cell--right").remove();

        copyHeaderRow(baseTable);
        if ($(e).find(".fixed-column--left").length > 0) {
            copyLeftHeaderCell(e);
        }
        if ($(e).find(".fixed-column--right").length > 0) {
            copyRightHeaderCell(e);
        }
    }

    // data-fixed_table属性を持っているテーブルがあれば初期化する
    if (baseTable.length > 0) {
        init();
    }
};
