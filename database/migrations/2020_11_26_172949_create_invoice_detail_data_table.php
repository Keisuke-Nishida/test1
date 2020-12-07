<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDetailDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_detail_data', function (Blueprint $table) {
            $table->integer('id')->comment('ID');
            $table->integer('invoice_data_id')->index()->comment('請求データID');
            $table->string('data_create_date', 10)->nullable()->comment('データ作成日');
            $table->string('order_line_no', 3)->nullable()->comment('受注行番号');
            $table->string('condition_code', 2)->nullable()->comment('状況コード');
            $table->string('jan_code', 13)->nullable()->comment('ＪＡＮコード');
            $table->string('item_code', 6)->nullable()->comment('品名コード');
            $table->string('packing_code', 5)->nullable()->comment('包装単位コード');
            $table->string('item_name', 80)->nullable()->comment('商品名');
            $table->decimal('item_quantity', 11, 2)->nullable()->comment('出荷数量');
            $table->string('unit_name', 4)->nullable()->comment('単位名');
            $table->decimal('sale_price', 12, 2)->nullable()->comment('売上単価');
            $table->decimal('discount_price', 12, 2)->nullable()->comment('歩引き後単価');
            $table->decimal('sale_amount', 13, 0)->nullable()->comment('売上金額');
            $table->string('detail_remarks', 40)->nullable()->comment('明細備考');
            $table->string('shipping_no', 20)->nullable()->comment('送り状ＮＯ');
            $table->string('item_lot', 12)->nullable()->comment('製品ロット');
            $table->string('expire_date', 6)->nullable()->comment('有効期限年月');
            $table->string('shipping_type', 2)->nullable()->comment('運送方法区分');
            $table->decimal('tax', 13, 0)->nullable()->comment('消費税');
            $table->string('shipping_place_type', 2)->nullable()->comment('出荷場所区分');
            $table->string('yobi', 338)->nullable()->comment('予備');
            $table->timestamp('created_at')->comment('作成日');
            $table->timestamp('updated_at')->nullable()->comment('更新日');
            $table->softDeletes()->comment('削除日');

            $table->primary(['id', 'data_create_date']);
        });

        Schema::table('invoice_detail_data', function (Blueprint $table) {
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
        Schema::dropIfExists('invoice_detail_data');
    }
}
