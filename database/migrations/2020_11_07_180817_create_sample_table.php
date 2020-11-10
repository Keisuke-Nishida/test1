<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample', function (Blueprint $table) {
            // ユーザーマスタ
            $table->increments('id')->comment('ID');
            $table->string('name', 50)->nullable()->comment('名前');
            $table->string('sample1', 50)->nullable()->comment('項目１');
            $table->string('sample2', 255)->nullable()->comment('項目２');
            $table->string('sample3', 255)->nullable()->comment('項目３');
            $table->timestamp('sample_time')->nullable()->comment('日付');
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
        Schema::dropIfExists('sample');
    }
}
