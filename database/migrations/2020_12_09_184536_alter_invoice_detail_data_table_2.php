<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInvoiceDetailDataTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 出荷詳細データ：不要項目の削除。追加項目
        Schema::table('invoice_detail_data', function (Blueprint $table) {
            $table->dropColumn('shipping_type');
            $table->dropColumn('shipping_place_type');

            $table->string('voucher_line_no', 3)->nullable()->after('data_create_date')->comment('伝票行番号');
            $table->string('voucher_no', 6)->nullable()->after('data_create_date')->comment('伝票番号');
            $table->string('reserve_type', 1)->nullable()->after('order_line_no')->comment('予約区分');
            $table->string('onebox_capacity', 10)->nullable()->after('packing_code')->comment('一箱容量');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_detail_data', function (Blueprint $table) {
            $table->string('shipping_type', 2)->nullable()->comment('運送方法区分');
            $table->string('shipping_place_type', 2)->nullable()->comment('出荷場所区分');

            $table->dropColumn('voucher_no');
            $table->dropColumn('voucher_line_no');
            $table->dropColumn('reserve_type');
            $table->dropColumn('onebox_capacity');
        });
    }
}
