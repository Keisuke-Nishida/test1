<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule', function (Blueprint $table) {
            // スケジュールマスタ
            $table->increments('id')->comment('ID');

            $table->string('name', 50)->nullable()->comment('スケジュール名');
            $table->integer('type')->comment('スケジュール種別');
            $table->string('start_time', 5)->comment('実行開始時間');
            $table->string('end_time', 5)->comment('実行終了時間');
            $table->integer('interval_minutes')->comment('実行間隔(分)');
            $table->integer('retry_count')->comment('リトライ回数');
            $table->integer('cancel_flag')->comment('当日キャンセルフラグ');
            $table->timestamp('last_run_time')->comment('最終実行日時');

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
        Schema::dropIfExists('schedule');
    }
}
