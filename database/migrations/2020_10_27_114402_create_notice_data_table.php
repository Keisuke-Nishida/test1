<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticeDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notice_data', function (Blueprint $table) {
            // お知らせデータ
            $table->increments('id')->comment('ID');

            $table->string('name', 50)->nullable()->comment('お知らせ名');
            $table->string('title', 255)->nullable()->comment('お知らせタイトル');
            $table->text('body')->nullable()->comment('お知らせ本文');
            $table->timestamp('start_time')->nullable()->comment('配信開始日時');
            $table->timestamp('end_time')->nullable()->comment('配信終了日時');

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
        Schema::dropIfExists('notice_data');
    }
}
