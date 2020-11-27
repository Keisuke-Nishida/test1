<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterShipmentDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 出荷詳細テーブルに移行する為削除
        Schema::table('shipment_data', function (Blueprint $table) {
            $table->dropColumn('condition_code');
            $table->dropColumn('transport_type');
            $table->dropColumn('order_line_no');
            $table->dropColumn('jan_code');
            $table->dropColumn('item_code');
            $table->dropColumn('packing_code');
            $table->dropColumn('item_name');
            $table->dropColumn('item_quantity');
            $table->dropColumn('unit_name');
            $table->dropColumn('order_price');
            $table->dropColumn('order_amount');
            $table->dropColumn('detail_remarks');
            $table->dropColumn('shipping_no');
            $table->dropColumn('item_lot');
            $table->dropColumn('expire_date');
            $table->dropColumn('shipping_type');
            $table->dropColumn('yobi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipment_data', function (Blueprint $table) {
            $table->string('condition_code', 2)->nullable()->comment('状況コード');
            $table->string('transport_type', 2)->nullable()->comment('扱便名区分');
            $table->string('order_line_no', 3)->nullable()->comment('受注行番号');
            $table->string('jan_code', 13)->nullable()->comment('ＪＡＮコード');
            $table->string('item_code', 6)->nullable()->comment('品名コード');
            $table->string('packing_code', 5)->nullable()->comment('包装単位コード');
            $table->string('item_name', 80)->nullable()->comment('商品名');
            $table->decimal('item_quantity', 11, 2)->nullable()->comment('受注数量');
            $table->string('unit_name', 8)->nullable()->comment('単位名');
            $table->decimal('order_price', 12, 2)->nullable()->comment('受注単価');
            $table->decimal('order_amount', 13, 0)->nullable()->comment('受注金額');
            $table->string('detail_remarks', 40)->nullable()->comment('明細備考');
            $table->string('shipping_no', 20)->nullable()->comment('送り状ＮＯ');
            $table->string('item_lot', 12)->nullable()->comment('製品ロット');
            $table->string('expire_date', 6)->nullable()->comment('有効期限年月');
            $table->string('shipping_type', 2)->nullable()->comment('運送方法区分');
            $table->string('yobi', 338)->nullable()->comment('予備');
        });
    }
}
