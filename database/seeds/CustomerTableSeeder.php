<?php

use Illuminate\Database\Seeder;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 得意先テーブル
        DB::table('customer')->insert([
            [
                'code'              => '0123456',
                'name'              => '株式会社テスト',
                'name_kana'         => 'カブシキガイシャテスト',
                'prefecture_id'     => 1,
                'post_no'           => '0608588',
                'address_1'         => '北海道札幌市中央区',
                'address_2'         => '北３条西６丁目',
                'tel'               => '0112314111',
                'fax'               => '0112314111',
                'kiduke_kanji'      => '株式会社Ａ　テスト部　テスト課',
                'uriage_1'          => null,
                'uriage_2'          => null,
                'uriage_3'          => null,
                'uriage_4'          => null,
                'uriage_5'          => null,
                'uriage_6'          => null,
                'uriage_7'          => null,
                'uriage_8'          => null,
                'core_system_status'  => 0,

                'created_at'        => 20200101,
                'created_by'        => 1,
                'updated_at'        => 20200101,
                'updated_by'        => 1,
                'deleted_at'        => null,
                'deleted_by'        => null
            ]
        ]);
    }
}
