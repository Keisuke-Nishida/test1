<?php

use Illuminate\Database\Seeder;

class VoucherTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $voucher_data = [
            ['code' => '00', 'name' => '売上'],
            ['code' => '01', 'name' => '取消'],
            ['code' => '02', 'name' => '数量訂正'],
            ['code' => '03', 'name' => '金額訂正'],
            ['code' => '05', 'name' => '数量追加'],
            ['code' => '06', 'name' => '金額追加'],
            ['code' => '10', 'name' => 'サンプル１'],
            ['code' => '11', 'name' => '再送依頼'],
            ['code' => '20', 'name' => '不足再送'],
            ['code' => '21', 'name' => '不足再送訂正'],
            ['code' => '60', 'name' => '戻り'],
            ['code' => '61', 'name' => '戻り訂正'],
            ['code' => '62', 'name' => '戻り追加（数量）'],
            ['code' => '63', 'name' => '戻り追加（金額）'],
            ['code' => '64', 'name' => '戻り（再送依頼）'],
            ['code' => '65', 'name' => '戻り訂正'],
            ['code' => '66', 'name' => '戻り訂正'],
            ['code' => '70', 'name' => '値引'],
            ['code' => '71', 'name' => '値引訂正']
        ];

        $date_data = [
            'created_at' => 20200101,
            'created_by' => 1,
            'updated_at' => 20200101,
            'updated_by' => 1,
            'deleted_at' => null,
            'deleted_by' => null
        ];

        foreach ($voucher_data as $value) {
            $voucher_array[] = array_merge($value, $date_data);
        }

        // 伝票区分マスタテーブル
        DB::table('voucher')->insert($voucher_array);
    }
}
