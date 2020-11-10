<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerDestinationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_destination', function (Blueprint $table) {
            // 得意先送り先マスタ
            $table->increments('id')->comment('ID');

            $table->integer('customer_id')->index()->comment('得意先ID');
            $table->string('code', 7)->index()->comment('得意先送り先コード');
            $table->string('name', 255)->nullable()->comment('得意先送り先名');
            $table->string('name_kana', 255)->nullable()->comment('得意先送り先名カナ');
            $table->integer('prefecture_id')->nullable()->comment('都道府県ID');
            $table->string('post_no', 7)->nullable()->comment('郵便番号');
            $table->string('address_1', 255)->nullable()->comment('住所1');
            $table->string('address_2', 255)->nullable()->comment('住所2');
            $table->string('tel', 20)->nullable()->comment('TEL');
            $table->string('fax', 20)->nullable()->comment('FAX');
            $table->string('kiduke_kanji', 50)->nullable()->comment('気付先漢字');

            $table->timestamp('created_at')->comment('作成日');
            $table->integer('created_by')->comment('作成者ユーザーID');
            $table->timestamp('updated_at')->nullable()->comment('更新日');
            $table->integer('updated_by')->nullable()->comment('更新者ログインID');
            $table->softDeletes()->comment('削除日');
            $table->integer('deleted_by')->nullable()->comment('削除者ユーザーID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_destination');
    }
}
