<?php
namespace App\Lib;
/**
 * メッセージクラス
 * Class Message
 * @package App\Lib
 */
class Message {

    const ERROR_001 = "%sは必須入力です";
    const ERROR_002 = "%sは%s桁まで入力可能です";
    const ERROR_003 = "%sの入力に誤りがあります";
    const ERROR_004 = "%sと%sが一致しません";
    const ERROR_005 = "%sは数値のみ入力可能です";
    const ERROR_006 = "%sは最低%s文字以上入力してください";
    const ERROR_007 = "ログイン情報がありません";
    const ERROR_008 = "%s must have %s characters";
    const ERROR_009 = "%s must be between %s to %s characters";
    const ERROR_010 = "%s already exists";
    const ERROR_011 = "Invalid file type. Allowed extentions: %s";
    const ERROR_012 = "File is too large. Maximum upload size is %sMB";

    const INFO_001  = "%sの登録を行いました";
    const INFO_002  = "%sの編集を行いました";
    const INFO_003  = "%sの削除を行いました";
    const INFO_004  = "%sのデータを削除します。よろしいですか？";
    const INFO_005 = "Go back to index page?";

    /**
     * メッセージ取得
     * @param $message
     * @param array $options
     * @return string
     */
    public static function getMessage($message, $options=[]) {
        if (count($options) > 0) {
            $message = vsprintf($message, $options);
        }
        return $message;
    }
}
