<?php

namespace App\Lib;

/**
 * メッセージクラス
 * Class Message
 * @package App\Lib
 */
class Message
{

    const ERROR_001 = "%sは必須入力です";
    const ERROR_002 = "%sは%s桁まで入力可能です";
    const ERROR_003 = "%sの入力に誤りがあります";
    const ERROR_004 = "%sと%sが一致しません";
    const ERROR_005 = "%sは数値のみ入力可能です";
    const ERROR_006 = "%sは最低%s文字以上入力してください";
    const ERROR_007 = "ログイン情報がありません";
    const ERROR_008 = "%sの書式が間違っています";
    const ERROR_009 = "利用規約に同意してください";
    const ERROR_010 = "既に登録済みの%sです";
    const ERROR_011 = "パスワード再発行URLが無効です。<br>※無効なURLまたはURLの有効期限が切れています";
    const ERROR_012 = "パスワードには、英大文字・英小文字・数字それぞれを最低1文字ずつ含む必要があります";
    const ERROR_013 = "%sは%s文字以下にしてください";
    const ERROR_014 = "%sは%sは異なる%sにしてください";
    const ERROR_015 = "%sを保存できませんでした";

    const INFO_001  = "%sの登録を行いました";
    const INFO_002  = "%sの編集を行いました";
    const INFO_003  = "%sの削除を行いました";
    const INFO_004  = "%sの削除を行います。よろしいですか？";
    const INFO_005  = "%sの印刷を行います。よろしいですか？";
    const INFO_006  = "%sが完了しました";
    const INFO_007  = "ご入力いただいたアドレスへメールを送信いたしました。<br>メールの内容をご確認の上、利用登録を行ってください";
    const INFO_008  = "メールアドレス認証が完了しました。<br>本サービスの利用が可能となります。";
    const INFO_009  = "ご入力いただいたアドレスへメールを送信いたしました。<br>メールの内容をご確認の上、パスワード再発行を行ってください";
    const INFO_010  = "パスワードの変更が完了しました。";
    const INFO_011  = "メールアドレス認証に失敗しました。<br>本サービスを利用される場合は再度メールアドレス認証を行ってください。";

    /**
     * メッセージ取得
     * @param $message
     * @param array $options
     * @return string
     */
    public static function getMessage($message, $options = [])
    {
        if (count($options) > 0) {
            $message = vsprintf($message, $options);
        }
        return $message;
    }
}
