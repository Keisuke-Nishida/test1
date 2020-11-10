<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_menu', function (Blueprint $table) {
            // 権限メニューマスタ
            $table->increments('id')->comment('ID');

            $table->integer('role_id')->index()->comment('権限ID');
            $table->integer('menu_id')->index()->comment('メニューID');

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
        Schema::dropIfExists('role_menu');
    }
}
