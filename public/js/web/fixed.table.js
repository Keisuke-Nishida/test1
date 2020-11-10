// 管理一覧の行列固定
$(function () {
  //行ヘッダーに対しtopを設定
  height = 0;
  for (var i = 0; i < fixed_header_num; i++) {
    $(".fixed-header-" + i + " th").css("top", height);
    height += $(".fixed-header-" + i + " th").outerHeight();
  }

  //列ヘッダーに対しleftを設定
  width = 0;
  for (var j = 0; j < fixed_column_left_num; j++) {
    $(".fixed-column-left-" + j).css("left", width);
    width += $("td.fixed-column-left-" + j).outerWidth(true);
  }
  //列ヘッダーに対しrightを設定
  width = 0;
  for (var k = 0; k < fixed_column_right_num; k++) {
    $(".fixed-column-right-" + k).css("right", width);
    width += $("td.fixed-column-right-" + k).outerWidth(true);
  }
});
