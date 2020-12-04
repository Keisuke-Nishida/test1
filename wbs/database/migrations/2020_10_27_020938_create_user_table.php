<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            // ユーザーマスタ
            $table->increments('id')->comment('ID');

            $table->string('name', 50)->nullable()->comment('ユーザー名');
            $table->string('login_id', 10)->nullable()->index()->comment('ログインID');
            $table->string('email', 255)->nullable()->index()->comment('メールアドレス');
            $table->string('password', 255)->nullable()->comment('パスワード');
            $table->rememberToken()->comment('リメンバートークン');
            $table->string('reset_token', 100)->nullable()->comment('リセットトークン');
            $table->timestamp('reset_token_limit_time')->nullable()->comment('リセットトークン有効期限日時');
            $table->timestamp('last_login_time')->nullable()->comment('最終ログイン日時');
            $table->integer('role_id')->comment('権限ID');
            $table->integer('status')->comment('ユーザーステータス');
            $table->integer('customer_id')->nullable()->comment('得意先ID');
            $table->integer('system_admin_flag')->comment('システム管理者フラグ');

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
        Schema::dropIfExists('user');
    }
}
