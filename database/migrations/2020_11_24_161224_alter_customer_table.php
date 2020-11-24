<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->renameColumn('core_system_flag', 'core_system_status');
        });
        Schema::table('customer', function (Blueprint $table) {
            $table->integer('core_system_status')->comment('基幹システム連携ステータス')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->renameColumn('core_system_status', 'core_system_flag');
        });
        Schema::table('customer', function (Blueprint $table) {
            $table->integer('core_system_flag')->comment('基幹システム連携フラグ')->change();
        });
    }
}
