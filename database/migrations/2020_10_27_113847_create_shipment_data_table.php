<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_data', function (Blueprint $table) {
            // 出荷データ
            $table->integer('id')->comment('ID');

            $table->string('data_no', 7)->nullable()->comment('データＮＯ');
            $table->string('data_type', 2)->nullable()->comment('データ種別');
            $table->string('process_type', 2)->nullable()->comment('処理区分');
            $table->string('condition_code', 2)->nullable()->comment('状況コード');
            $table->string('data_create_date', 10)->nullable()->comment('データ作成日');
            $table->string('data_create_time', 6)->nullable()->comment('データ作成時刻');
            $table->string('operator_no', 8)->nullable()->comment('オペレータＮＯ');
            $table->string('customer_code', 7)->nullable()->index()->comment('得意先コード');
            $table->string('sale_tel', 15)->nullable()->comment('売上先電話番号');
            $table->string('sale_name_kana', 30)->nullable()->comment('売上先名カナ');

            $table->string('sale_name', 30)->nullable()->comment('売上先名漢字');
            $table->string('sale_prefecture_code', 2)->nullable()->comment('売上先県コード');
            $table->string('sale_post_no', 7)->nullable()->comment('売上先郵便番号');
            $table->string('sale_address_1', 30)->nullable()->comment('売上先住所１漢字');
            $table->string('sale_address_2', 30)->nullable()->comment('売上先住所２漢字');
            $table->string('destination_code', 7)->nullable()->comment('送り先コード');
            $table->string('destination_tel', 15)->nullable()->comment('送り先電話番号');
            $table->string('destination_name_kana', 30)->nullable()->comment('送り先名カナ');
            $table->string('destination_name', 30)->nullable()->comment('送り先名漢字');
            $table->string('destination_prefecture_code', 2)->nullable()->comment('送り先県コード');

            $table->string('destination_post_no', 7)->nullable()->comment('送り先郵便番号');
            $table->string('destination_address_1', 30)->nullable()->comment('送り先住所１漢字');
            $table->string('destination_address_2', 30)->nullable()->comment('送り先住所２漢字');
            $table->string('kiduke_kanji', 30)->nullable()->comment('気付先漢字');
            $table->string('transport_type', 2)->nullable()->comment('扱便名区分');
            $table->string('voucher_remark_a', 30)->nullable()->comment('伝票備考Ａ');
            $table->string('voucher_remark_b', 100)->nullable()->comment('伝票備考Ｂ');
            $table->string('order_no', 7)->nullable()->comment('受注ＮＯ');
            $table->string('order_line_no', 3)->nullable()->comment('受注行番号');
            $table->string('order_date', 10)->nullable()->comment('受注日');

            $table->string('order_confirm_date', 10)->nullable()->comment('受注確定日');
            $table->string('shipment_date', 10)->nullable()->comment('出荷日');
            $table->string('instruction_no', 10)->nullable()->comment('指図番号');
            $table->string('jan_code', 13)->nullable()->comment('ＪＡＮコード');
            $table->string('item_code', 6)->nullable()->comment('品名コード');
            $table->string('packing_code', 5)->nullable()->comment('包装単位コード');
            $table->string('item_name', 80)->nullable()->comment('商品名');
            $table->decimal('item_quantity', 11, 2)->nullable()->comment('受注数量');
            $table->string('unit_name', 8)->nullable()->comment('単位名');
            $table->decimal('order_price', 12, 2)->nullable()->comment('受注単価');

            $table->decimal('order_amount', 13, 0)->nullable()->comment('受注金額');
            $table->string('detail_remarks', 40)->nullable()->comment('明細備考');
            $table->string('delivery_date', 10)->nullable()->comment('納期');
            $table->string('delivery_type', 1)->nullable()->comment('納期区分');
            $table->string('delivery_type_name', 20)->nullable()->comment('納期区分名');
            $table->string('answer_delivery', 20)->nullable()->comment('回答納期');
            $table->string('answer_delivery_date', 8)->nullable()->comment('回答納期－年月日');
            $table->string('answer_delivery_detail', 40)->nullable()->comment('回答納期－詳細');
            $table->string('price_type', 1)->nullable()->comment('値入基準区分');
            $table->string('shipping_no', 20)->nullable()->comment('送り状ＮＯ');

            $table->string('item_lot', 12)->nullable()->comment('製品ロット');
            $table->string('expire_date', 6)->nullable()->comment('有効期限年月');
            $table->string('reserve_type', 1)->nullable()->comment('予約区分');
            $table->string('voucher_type', 2)->nullable()->comment('伝票区分');
            $table->string('shipping_type', 2)->nullable()->comment('運送方法区分');
            $table->string('yobi', 338)->nullable()->comment('予備');

            $table->timestamp('created_at')->comment('作成日');
            // $table->integer('created_by')->comment('作成者ユーザーID');
            $table->timestamp('updated_at')->nullable()->comment('更新日');
            // $table->integer('updated_by')->nullable()->comment('更新者ログインID');
            $table->softDeletes()->comment('削除日');
            // $table->integer('deleted_by')->nullable()->comment('削除者ユーザーID');

            $table->primary(['id', 'data_create_date']);
        });

        // laravelのマイグレーションで複合主キーを作れないので
        // カラムを生成してからオートインクリメントに変更する
        Schema::table('shipment_data', function (Blueprint $table) {
            $table->increments('id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipment_data');
    }
}
