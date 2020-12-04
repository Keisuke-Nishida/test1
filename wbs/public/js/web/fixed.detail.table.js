// 詳細一覧のヘッダー行の固定
$(function () {
  var $thead = $(".main-list__detail__table").find("thead");
  $(".main-list__detail__table").scroll(function () {
    $thead.css("transform", "translateY(" + this.scrollTop + "px)");
  });
});
