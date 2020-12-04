<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            // 得意先マスタ
            $table->increments('id')->comment('ID');

            $table->string('code', 7)->index()->comment('得意先コード');
            $table->string('name', 255)->nullable()->comment('得意先名');
            $table->string('name_kana', 255)->nullable()->comment('得意先名カナ');
            $table->integer('prefecture_id')->nullable()->comment('都道府県ID');
            $table->string('post_no', 7)->nullable()->comment('郵便番号');
            $table->string('address_1', 255)->nullable()->comment('住所1');
            $table->string('address_2', 255)->nullable()->comment('住所2');
            $table->string('tel', 20)->nullable()->comment('TEL');
            $table->string('fax', 20)->nullable()->comment('FAX');
            $table->string('kiduke_kanji', 50)->nullable()->comment('気付先漢字');
            $table->string('uriage_1', 2)->nullable()->comment('売上口座区分１');
            $table->string('uriage_2', 2)->nullable()->comment('売上口座区分２');
            $table->string('uriage_3', 2)->nullable()->comment('売上口座区分３');
            $table->string('uriage_4', 2)->nullable()->comment('売上口座区分４');
            $table->string('uriage_5', 2)->nullable()->comment('売上口座区分５');
            $table->string('uriage_6', 2)->nullable()->comment('売上口座区分６');
            $table->string('uriage_7', 2)->nullable()->comment('売上口座区分７');
            $table->string('uriage_8', 2)->nullable()->comment('売上口座区分８');
            $table->integer('core_system_flag')->default(0)->comment('基幹システム連携フラグ');

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
        Schema::dropIfExists('customer');
    }
}
