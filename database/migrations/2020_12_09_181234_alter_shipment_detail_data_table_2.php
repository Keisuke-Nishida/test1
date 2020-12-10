<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterShipmentDetailDataTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 出荷詳細データ：不要項目の削除。追加項目
        Schema::table('shipment_detail_data', function (Blueprint $table) {
            $table->string('transport_type', 20)->comment('扱便名')->change(); // 扱便名：名称保持のため桁数上げ
            $table->string('shipping_type', 20)->comment('運送方法')->change(); // 運送方法：名称保持のため桁数上げ
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipment_detail_data', function (Blueprint $table) {
            $table->string('transport_type', 2)->change();
            $table->string('shipping_type', 2)->change();
        });
    }
}
