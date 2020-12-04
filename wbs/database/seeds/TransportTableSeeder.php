<?php

use Illuminate\Database\Seeder;

class TransportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transport_data = [
            ['code' => '01', 'name' => '西濃運輸'],
            ['code' => '02', 'name' => '京成運輸'],
            ['code' => '03', 'name' => '佐川急便'],
            ['code' => '04', 'name' => '久留米運送'],
            ['code' => '05', 'name' => '福山通運'],
            ['code' => '06', 'name' => '岡山県貨物'],
            ['code' => '07', 'name' => '野々村運送'],
            ['code' => '08', 'name' => '西濃（名古屋西）'],
            ['code' => '09', 'name' => '中央便'],
            ['code' => '10', 'name' => 'トール'],
            ['code' => '11', 'name' => '西濃（園芸部）'],
            ['code' => '12', 'name' => '阪急国内空輸'],
            ['code' => '13', 'name' => '丸工運送'],
            ['code' => '14', 'name' => '使用禁止'],
            ['code' => '15', 'name' => 'エスラインギフ'],
            ['code' => '16', 'name' => '福山（宅配）'],
            ['code' => '17', 'name' => '使用禁止'],
            ['code' => '18', 'name' => '京都運輸'],
            ['code' => '19', 'name' => '日本通運'],
            ['code' => '20', 'name' => '西濃（直売部）'],
            ['code' => '21', 'name' => '大島運輸'],
            ['code' => '22', 'name' => '西濃（彦根）'],
            ['code' => '23', 'name' => '近鉄物流'],
            ['code' => '24', 'name' => 'ヤマト運輸'],
            ['code' => '25', 'name' => '丸三海運'],
            ['code' => '26', 'name' => '琉球通運'],
            ['code' => '27', 'name' => '大商海運'],
            ['code' => '28', 'name' => '【離島】'],
            ['code' => '29', 'name' => '使用禁止'],
            ['code' => '30', 'name' => '近鉄第一トラック'],
            ['code' => '31', 'name' => '信州名鉄'],
            ['code' => '32', 'name' => '使用禁止'],
            ['code' => '33', 'name' => '第一貨物'],
            ['code' => '34', 'name' => '日本新潟運輸'],
            ['code' => '35', 'name' => '福井配送'],
            ['code' => '36', 'name' => 'マルニ'],
            ['code' => '37', 'name' => 'ヨナイザワ'],
            ['code' => '38', 'name' => '札幌急配'],
            ['code' => '39', 'name' => '京都通運'],
            ['code' => '40', 'name' => 'シズナイロゴス'],
            ['code' => '41', 'name' => '八潮運輸'],
            ['code' => '42', 'name' => '松運'],
            ['code' => '43', 'name' => '曙商事'],
            ['code' => '44', 'name' => '東栄運送'],
            ['code' => '45', 'name' => '三八五流通'],
            ['code' => '46', 'name' => '博運社'],
            ['code' => '47', 'name' => '九州産交運輸'],
            ['code' => '48', 'name' => '千石西濃運輸'],
            ['code' => '49', 'name' => '宮崎運輸'],
            ['code' => '50', 'name' => '野母商船'],
            ['code' => '51', 'name' => '南国海運'],
            ['code' => '52', 'name' => '壱岐海運'],
            ['code' => '53', 'name' => '松岡満'],
            ['code' => '54', 'name' => '新潟運輸'],
            ['code' => '55', 'name' => 'トナミ運輸'],
            ['code' => '56', 'name' => '西武運輸'],
            ['code' => '57', 'name' => '山陽自動車'],
            ['code' => '58', 'name' => '名鉄運輸'],
            ['code' => '59', 'name' => '小林運輸'],
            ['code' => '60', 'name' => 'クロネコメール便'],
            ['code' => '61', 'name' => '北九州宮崎運送'],
            ['code' => '62', 'name' => 'ナビックス'],
            ['code' => '63', 'name' => '札樽自動車'],
            ['code' => '64', 'name' => '月寒運輸'],
            ['code' => '65', 'name' => 'ネコポス'],
            ['code' => '66', 'name' => '日硝ハイウエー'],
        ];

        $date_data = [
            'created_at' => 20200101,
            'created_by' => 1,
            'updated_at' => 20200101,
            'updated_by' => 1,
            'deleted_at' => null,
            'deleted_by' => null
        ];

        foreach ($transport_data as $value) {
            $transport_array[] = array_merge($value, $date_data);
        }

        // 扱便マスタテーブル
        DB::table('transport')->insert($transport_array);
    }
}
