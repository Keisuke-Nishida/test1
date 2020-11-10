// ローディング画面
function displayLoading() {
  // ローディング画面の表示位置設定
  $("#loading_bg").fadeIn(200);
  // 画面を固定してスクロールなどの禁止
  $("body").css("overflow", "hidden");
}

function hideLoading() {
  $("body").css("overflow", "auto");
  $("#loading_bg").delay(100).fadeOut(200);
}

// datetimepicker関連
$(function () {
  $.datetimepicker.setLocale("ja");

  // 処理日
  $("#processing_date_start").datetimepicker({
    format: "Y/m/d",
    onShow: function (ct) {
      this.setOptions({
        maxDate: $("#processing_date_end").val()
          ? $("#processing_date_end").val()
          : false,
      });
    },
    timepicker: false,
  });
  $("#processing_date_end").datetimepicker({
    format: "Y/m/d",
    onShow: function (ct) {
      this.setOptions({
        minDate: $("#processing_date_start").val()
          ? $("#processing_date_start").val()
          : false,
      });
    },
    timepicker: false,
  });

  // 出荷日
  $("#shipment_date_start").datetimepicker({
    format: "Y/m/d",
    onShow: function (ct) {
      this.setOptions({
        maxDate: $("#shipment_date_end").val()
          ? $("#shipment_date_end").val()
          : false,
      });
    },
    timepicker: false,
  });
  $("#shipment_date_end").datetimepicker({
    format: "Y/m/d",
    onShow: function (ct) {
      this.setOptions({
        minDate: $("#shipment_date_start").val()
          ? $("#shipment_date_start").val()
          : false,
      });
    },
    timepicker: false,
  });

  // 受注確定日
  $("#order_confirmed_date_start").datetimepicker({
    format: "Y/m/d",
    onShow: function (ct) {
      this.setOptions({
        maxDate: $("#order_confirmed_date_end").val()
          ? $("#order_confirmed_date_end").val()
          : false,
      });
    },
    timepicker: false,
  });
  $("#order_confirmed_date_end").datetimepicker({
    format: "Y/m/d",
    onShow: function (ct) {
      this.setOptions({
        minDate: $("#order_confirmed_date_start").val()
          ? $("#order_confirmed_date_start").val()
          : false,
      });
    },
    timepicker: false,
  });
});

// 詳細検索欄の開閉
// $("#detailedSearch").on("hidden.bs.collapse", function () {});
