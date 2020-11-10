<?php

use Illuminate\Database\Seeder;

class ConditionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $condition_data = [
            ['code' => '00', 'name' => '未確定',],
            ['code' => '10', 'name' => '承認待ち',],
            ['code' => '20', 'name' => '受注確定済',],
            ['code' => '30', 'name' => '手動発注',],
            ['code' => '35', 'name' => '自動発注',],
            ['code' => '40', 'name' => '仕入済',],
            ['code' => '49', 'name' => '仕入確定',],
            ['code' => '50', 'name' => '配分済',],
            ['code' => '55', 'name' => '出荷指示済',],
            ['code' => '60', 'name' => '出荷済',],
            ['code' => '70', 'name' => '売上済',],
            ['code' => '99', 'name' => '売上確定',],
        ];

        $date_data = [
            'created_at' => 20200101,
            'created_by' => 1,
            'updated_at' => 20200101,
            'updated_by' => 1,
            'deleted_at' => null,
            'deleted_by' => null
        ];

        foreach ($condition_data as $value) {
            $condition_array[] = array_merge($value, $date_data);
        }

        // 状況マスタテーブル
        DB::table('condition')->insert($condition_array);
    }
}
