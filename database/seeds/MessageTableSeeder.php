<?php

use Illuminate\Database\Seeder;

class MessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 得意先テーブル
        DB::table('message')->insert([
            [
                'name'       => '利用規約',
                'key'        => '1',
                'value'      => '<h5>【プライバシーについて】</h5>
                            <p>当社は、別途掲載の「プライバシーポリシー（個人情報の取扱い）について」 に基づき、適切に取り扱うものとします。</p>
                            <h5>【電子的コミュニケーション】</h5>
                            <p>当社サイトで提供するコンテンツや情報などに関する著作権は、当社に帰属します。そのため、当社サイトのコンテンツや情報を、私的利用など法律によって認められる範囲を超えて、複製、転用、販売等で使用することはできません。また、当社からお客様にお送りした電子メールに関しても同様に著作権は全て当社に帰属し、当社に無断で使用することはできません。
                            </p>
                            <h5>【著作権】</h5>
                            <p>当社サイトで使用のトレードマークやサービスマークなどの商標は、当社または当社に使用を認めた権利者に帰属し、無断使用はできません。</p>
                            <h5>個人情報保護の方針と取扱いについて</h5>
                            <p>タキイ種苗株式会社（以下「弊社」といいます）は、お客様個人に関する情報（以下「個人情報」といいます）の重要性を認識し、その保護の徹底を図り、お客様の信頼を得るため、関係する法律やガイドラインなどを遵守します。以下にその基本方針を掲げこれを徹底いたします。
                            </p>
                            <h5>【個人情報の収集と登録】</h5>
                            <p>お客様の個人情報は、以下の弊社の事業活動範囲内でお預りし、弊社のコンピューターに登録、または書類としてファイルさせていただきます。公開された情報を除き、適法かつ公正な手段で収集し、お客様のご了解なしに第三者から個人情報を入手、利用することはございません。
                            </p>
                            <p>ご注文、友の会ご入会、カタログ申込みの際にご記入いただく情報、並びに、ネット通販で顧客登録いただく情報。 お客様のご都合で、変更などの届け出・通知をいただく情報。
                                お客様からのお問い合わせの際にいただく情報。 お客様から指定発送先としていただく情報。 アンケート、クイズ、プレゼント企画など、ご応募いただく情報。
                                ご家族、ご親戚、お知り合いなどからお客様へのカタログ贈呈依頼をいただく情報。 弊社とのお取引、商談、取材を通じていただく名刺や情報。 試作、栽培委託などを通じていただく情報。
                            </p>
                            <h5>【使用目的】</h5>
                            <p>お客様の個人情報は使用目的を明確に定め、以下の目的、並びにこれらを円滑に実施するために付随する管理業務について、最低限必要な情報のみ使用いたします。</p>
                            <p>カタログ、月刊誌、DM、案内等の発送など、お客様への商品提案。 納品書、請求書、受注明細書、自動払込の案内やお支払いなどに関する業務。 商品の発送に関する業務。
                                お問い合わせへの回答。 アンケートやキャンペーンの実施、案内、賞品の発送に関する業務。 お客様のご了解をいただいた範囲内での月刊誌やカタログへの掲載。
                                試作、栽培委託に関する業務。 個人情報の提供 お客様の個人情報は次の場合を除き第三者に提供することはございません。 お客様のご了解をいただいたうえで第三者に提供する場合。
                                公的機関や弁護士会などから法令に基づく照会、開示要請を受け、弊社が必要と認める場合。
                                お客様への適正な与信判断のため、個人信用情報機関に照会し、登録されているお客様の個人情報を利用する場合（与信判断とは、ご利用限度額などを弊社で決定させていただくことをいいます）。
                                情報の取扱い、保護策 お客様の個人情報を厳重に保護するため、保管、事務における安全対策の実行と社員の教育、意識の徹底を図ります。
                                弊社は個人情報を取扱うにあたり管理責任者を置き、その管理責任者に個人情報の適切な管理を行わせます。
                                弊社システムは複数のチェック機能とファイヤーウォール（制御機能）を備え、外部からの不正アクセス防止に努めています。
                                データ、リストのプリント作成については担当者を限定し、情報保管における安全性の向上を図っています。
                                外部に委託処理をする場合には、十分な情報保護ができる委託先を選定し、委託契約などにおいて個人情報の管理に必要な事項を取り決め、適切な管理を指導、徹底します。
                                これらのことに関して継続的、恒常的な見直しを行い、個人情報の一層の保護に努めます。 個人情報の開示、訂正など
                                お客様の個人情報は正確に管理しております。お客様よりご本人の情報について開示の請求があれば、ご本人であることを確認させていただいたうえでご覧いただき、万一誤りがございましたら迅速に訂正いたします。
                                また、ご本人からのご指示があれば使用停止または削除いたします。</p>
                            <h5>【個人情報の開示等のご請求について】</h5>
                            <p>弊社では、個人情報保護法に基づき、下記の要領でお客様の個人情報の開示などを行います。個人情報開示などのご請求は封書によるもののみお受けいたします。電話、FAX、電子メールでの請求ではお答えできませんので、あらかじめご了承いただきますようお願い申し上げます。
                                なお、通信販売カタログや月刊誌などの送付、住所変更や停止など、通信販売に関する日常業務上のご連絡は、従来通り、綴じ込みの葉書などでいただければご対応いたしますので、この開示などの手続きは必要ございません。
                            </p>
                            <h5>【個人情報の開示等について】</h5>
                            <p>窓口 個人情報に関するお問い合わせ、開示等のご請求は弊社、通販係まで封書でお願いいたします。 〒600−8686　京都市下京区梅小路通猪熊東入　タキイ種苗（株）通販係</p>
                            <h5>【開示】</h5>
                            <p>以下の内容について個人情報を開示いたします。 お客様の個人情報の有無 保有していない場合は、その旨の連絡をいたします。 お客様の個人情報を保有している場合
                                お名前、ご住所、電話番号、生年月日、年齢、メールアドレス（ご登録がある場合のみ）、お客様番号（通信販売ご利用のお客様でご希望の場合のみ）、過去２年間以内のお取引履歴（通信販売ご利用のお客様でご希望の場合のみ）
                                正確性の保持 お客様の個人情報について追加、修正をいたします。 個人情報の使用停止または削除 お客様のご希望により個人情報の使用を停止または削除いたします。</p>
                            <h5>【手数料】</h5>
                            <p>無料。ただし事前に予告のうえ有料に改定させていただくことがあります。</p>
                            <h5>【その他】</h5>
                            <p>お返事までに要する期間は原則的に２週間以内とさせていただきます。調査に時間を要する場合は、それ以上のお時間をいただくことがありますので、あらかじめご了承をお願い申し上げます。
                            </p>
                            <h5>【開示等のご請求について】</h5>
                            <p>お客様の個人情報の開示などをご請求の際には下記の事項を規定の申込書にご記入いただき、ご本人様を確認できる書類とともに、通販係まで必ず封書でお送りください。ご本人様または代理人様の確認がとれない場合は開示いたしかねますのでご了承をお願い申し上げます。
                            </p>
                            <h5>開示等申込み書はこちら</h5>
                            <p>ご請求の際にご記入いただく事項 お名前、ご住所、電話番号 開示申込者、開示対象者について必ずご記入ください。 ご請求される内容について
                                規定の申込書に従い、内容をご記入ください。申込書がご入用の折は通販係にご連絡ください。 ご本人様確認できる書類
                                住民票の写し（発行から3カ月以内）、または運転免許証、健康保険の被保険者証、旅券（パスポート）などのコピー、いずれか１枚同封をお願い申し上げます。 代理人によるご請求の場合
                                上記“a、b”の書類のほかに、以下の書類の同封をお願いいたします。 代理人への委任状 代理人への委任状はこちら 代理人の本人確認ができる書類
                                住民票の写し（発行から3カ月以内）、または運転免許証、健康保険の被保険者証、旅券（パスポート）などのコピー、いずれか１枚、またはその他ご本人が確認できる書類の同封をお願い申し上げます。
                                お返事のお届け先 ご本人様確認用書類に記載されているご住所に、封書でお返事をお届けいたします。</p>
                            <h5>web上での取扱い</h5>
                            <h5>【セキュリティーについて】</h5>
                            <p>セキュリティーについて
                                弊社では、個人情報の通信に際し暗号化通信を採用し、お客様の大切な情報が盗まれたり改ざんされないよう努めています（ただし、インターネット上では、上記の対策や複数のチェック機能、ファイヤーウォールによってもセキュリティーが完全であるとは断言し得ないことから、悪意の第三者による被害の防止の保証はいたしかねますのであらかじめご了承をお願い申し上げます）。
                                サイトへのアクセスログを取得しており、弊社サイトの運用に関する分析、資料作成並びに不正アクセスの調査などに利用いたします。
                                弊社は、弊社のウェブページにリンクされているほかのサイトにおけるお客様の個人情報の保護については責任を負うものではございません。</p>
                            <h5>【クッキー（Cookie）について】</h5>
                            <p>弊社のウェブページは、Cookie情報を取得し、ウェブページの利用状況に関する統計分析や広告掲載等に利用する場合があります。その場合、弊社または第三者配信事業者によりインターネット上の様々なウェブページに弊社広告が掲載されます。これはお客さまにより良い情報やサービスをご提供していくことを目的としており、お客さま個人を特定する情報の収集はいたしません。Cookieの機能を無効にする方法はご利用のwebブラウザのサポートページをご確認ください。
                            </p>
                            <p>なお、弊社は、広告の配信を委託する第三者への委託に基づき、第三者を経由して、Cookie情報を保存し、参照する場合があります。</p>
                            <p>以上のプライバシーポリシーは、お客様へのより良いサービス提供や、法令、ガイドラインの改訂があった時などに、見直し、改善されることがあります。あらかじめご了承をお願い申し上げます。
                            </p>
                            <h5>【ネット通販における個人情報の確認・変更・削除】</h5>
                            <p>ご登録いただいている個人情報は、タキイネット通販にログインし、マイページ内の「ユーザー情報の確認・変更」よりいつでも確認・変更が可能です。
                                お客様がご登録いただいているユーザー登録情報の削除を希望される場合には、タキイネット通販にログインし、マイページ内の「ユーザー情報の確認・変更」より手続きを行ってください。<br><br>
                                個人情報に関するお問い合わせ先<br> 〒600−8686　京都市下京区梅小路通猪熊東入　タキイ種苗（株）通販係<br> TEL：075−365−0140<br>
                                FAX：075−344−6707<br> 電話受付時間：午前9:00～午後5:00<br> 休日：土曜・日曜・祝日・盆・年末年始・弊社の休業日<br>
                                ネットでのお問い合わせはこちらへ</p>',

                'created_at'        => 20200101,
                'created_by'        => 1,
                'updated_at'        => 20200101,
                'updated_by'        => 1,
                'deleted_at'        => null,
                'deleted_by'        => null
            ]
        ]);
    }
}
