<?php

use Illuminate\Database\Seeder;

class SampleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ユーザーテーブル
        DB::table('sample')->insert([
            [
                'name'                      => '名前',
                'sample1'                   => 'サンプル１',
                'sample2'                   => 'サンプル２',
                'sample3'                   => 'サンプル３',
                'sample_time'               => 20201111,
                'created_at'                => 20200101,
                'created_by'                => 1,
                'updated_at'                => 20200101,
                'updated_by'                => 1,
                'deleted_at'                => null,
                'deleted_by'                => null
            ],
            [
                'name'                      => '名前',
                'sample1'                   => 'サンプル１',
                'sample2'                   => 'サンプル２',
                'sample3'                   => 'サンプル３',
                'sample_time'               => 20201111,
                'created_at'                => 20200101,
                'created_by'                => 1,
                'updated_at'                => 20200101,
                'updated_by'                => 1,
                'deleted_at'                => null,
                'deleted_by'                => null
            ],
            [
                'name'                      => '名前',
                'sample1'                   => 'サンプル１',
                'sample2'                   => 'サンプル２',
                'sample3'                   => 'サンプル３',
                'sample_time'               => 20201111,
                'created_at'                => 20200101,
                'created_by'                => 1,
                'updated_at'                => 20200101,
                'updated_by'                => 1,
                'deleted_at'                => null,
                'deleted_by'                => null
            ],
            [
                'name'                      => '名前',
                'sample1'                   => 'サンプル１',
                'sample2'                   => 'サンプル２',
                'sample3'                   => 'サンプル３',
                'sample_time'               => 20201111,
                'created_at'                => 20200101,
                'created_by'                => 1,
                'updated_at'                => 20200101,
                'updated_by'                => 1,
                'deleted_at'                => null,
                'deleted_by'                => null
            ],
        ]);
    }
}
