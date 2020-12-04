<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ユーザーテーブル
        DB::table('user')->insert([
            [
                'name'                      => 'システム管理者',
                'login_id'                  => 'admin001',
                'email'                     => 'system_admin@example.com',
                'password'                  => Hash::make('test1234'),
                'remember_token'            => null,
                'reset_token'               => null,
                'reset_token_limit_time'    => null,
                'last_login_time'           => null,
                'role_id'                   => 1,
                'status'                    => 1,
                'customer_id'               => null,
                'system_admin_flag'         => 1,
                'created_at'                => 20200101,
                'created_by'                => 1,
                'updated_at'                => 20200101,
                'updated_by'                => 1,
                'deleted_at'                => null,
                'deleted_by'                => null
            ],
            [
                'name'                      => '一般ユーザー',
                'login_id'                  => 'user001',
                'email'                     => 'general_user@example.com',
                'password'                  => Hash::make('test1234'),
                'remember_token'            => null,
                'reset_token'               => null,
                'reset_token_limit_time'    => null,
                'last_login_time'           => null,
                'role_id'                   => 2,
                'status'                    => 2,
                'customer_id'               => 1,
                'system_admin_flag'         => 2,
                'created_at'                => 20200101,
                'created_by'                => 1,
                'updated_at'                => 20200101,
                'updated_by'                => 1,
                'deleted_at'                => null,
                'deleted_by'                => null
            ]
        ]);
    }
}
