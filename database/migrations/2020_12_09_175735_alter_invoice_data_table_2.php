<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInvoiceDataTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 請求データ：不要項目の削除。追加項目
        Schema::table('invoice_data', function (Blueprint $table) {
            $table->dropColumn('sale_tel');
            $table->dropColumn('sale_name_kana');
            $table->dropColumn('sale_name');
            $table->dropColumn('sale_prefecture_code');
            $table->dropColumn('sale_post_no');
            $table->dropColumn('sale_address_1');
            $table->dropColumn('sale_address_2');

            $table->dropColumn('destination_tel');
            $table->dropColumn('destination_name_kana');
            $table->dropColumn('destination_name');
            $table->dropColumn('destination_prefecture_code');
            $table->dropColumn('destination_post_no');
            $table->dropColumn('destination_address_1');
            $table->dropColumn('destination_address_2');

            $table->dropColumn('voucher_remark_a');
            $table->dropColumn('voucher_remark_b');

            $table->dropColumn('reserve_type'); // 予約区分：請求詳細に移動


            $table->string('transport_type', 20)->comment('扱便名')->change(); // 扱便名：名称保持のため桁数上げ
            $table->string('pieces', 3)->change(); // 個口数：桁数上げ

            $table->string('remarks', 60)->nullable()->after('division')->comment('備考');
            $table->string('shipment_place', 20)->nullable()->after('division')->comment('出荷場所');
            $table->decimal('shipping_amount', 7, 0)->nullable()->after('division')->comment('送料');
            $table->string('shipping_assignment', 20)->nullable()->after('division')->comment('運送指定');
            $table->string('shipping_type', 20)->nullable()->after('division')->comment('運送方法'); // 運送方法区分→運送方法：名称保持のため桁数上げ
            $table->string('fare_type', 20)->nullable()->after('division')->comment('運賃区分');
            $table->string('sale_date', 6)->nullable()->after('division')->comment('売上計上日');
            $table->string('voucher_no', 6)->nullable()->after('division')->comment('伝票番号');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_data', function (Blueprint $table) {

            $table->string('sale_tel', 15)->nullable()->comment('売上先電話番号');
            $table->string('sale_name_kana', 30)->nullable()->comment('売上先名カナ');
            $table->string('sale_name', 30)->nullable()->comment('売上先名漢字');
            $table->string('sale_prefecture_code', 2)->nullable()->comment('売上先県コード');
            $table->string('sale_post_no', 7)->nullable()->comment('売上先郵便番号');
            $table->string('sale_address_1', 30)->nullable()->comment('売上先住所１漢字');
            $table->string('sale_address_2', 30)->nullable()->comment('売上先住所２漢字');

            $table->string('destination_tel', 15)->nullable()->comment('送り先電話番号');
            $table->string('destination_name_kana', 30)->nullable()->comment('送り先名カナ');
            $table->string('destination_name', 30)->nullable()->comment('送り先名漢字');
            $table->string('destination_prefecture_code', 2)->nullable()->comment('送り先県コード');
            $table->string('destination_post_no', 7)->nullable()->comment('送り先郵便番号');
            $table->string('destination_address_1', 30)->nullable()->comment('送り先住所１漢字');
            $table->string('destination_address_2', 30)->nullable()->comment('送り先住所２漢字');

            $table->string('voucher_remark_a', 30)->nullable()->comment('伝票備考Ａ');
            $table->string('voucher_remark_b', 30)->nullable()->comment('伝票備考Ｂ');

            $table->string('reserve_type', 1)->nullable()->comment('予約区分');


            $table->string('transport_type', 2)->change();
            $table->string('pieces', 2)->change();

            $table->dropColumn('shipping_type');
            $table->dropColumn('voucher_no');
            $table->dropColumn('sale_date');
            $table->dropColumn('shipping_assignment');
            $table->dropColumn('shipping_amount');
            $table->dropColumn('fare_type');
            $table->dropColumn('shipment_place');
            $table->dropColumn('remarks');
        });
    }
}
