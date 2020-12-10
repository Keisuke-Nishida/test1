<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTableTransport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('transport');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('transport', function (Blueprint $table) {
            // 扱便マスタ
            $table->increments('id')->comment('ID');

            $table->string('code', 2)->nullable()->index()->comment('扱便コード');
            $table->string('name', 50)->nullable()->comment('扱便名');

            $table->timestamp('created_at')->comment('作成日');
            $table->integer('created_by')->comment('作成者ユーザーID');
            $table->timestamp('updated_at')->nullable()->comment('更新日');
            $table->integer('updated_by')->nullable()->comment('更新者ログインID');
            $table->softDeletes()->comment('削除日');
            $table->integer('deleted_by')->nullable()->comment('削除者ユーザーID');
        });
    }
}
