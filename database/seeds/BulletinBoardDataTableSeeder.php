<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;


class BulletinBoardDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start = new Carbon("2020-01-01 00:00:00");
        $startUnix = $start->format("U");
        $end = new Carbon("2021-12-31 23:59:59");
        $endUnix = $end->format("U");

        $faker_jp = Faker\Factory::create('ja_JP');

        $bulletin_board_data = [];
        for ($i = 0; $i < 100; $i++) {
            $randDateX = mt_rand($startUnix, $endUnix);
            $randDateY = mt_rand($startUnix, $endUnix);
            if ($randDateX < $randDateY) {
                $start_time = new Carbon($randDateX);
                $end_time = new Carbon($randDateY);
            } else {
                $start_time = new Carbon($randDateY);
                $end_time = new Carbon($randDateX);
            }
            $bulletin_board_data[] = [
                'name'            => $faker_jp->realText(random_int(25, 50), 2),
                'title'           => $faker_jp->realText(random_int(25, 255), 2),
                'body'            => $faker_jp->realText(random_int(500, 1000), 2),
                'file_name'       => "",
                'image_file_name' => "",
                'start_time'      => $start_time->format("Y-m-d H:i:s"),
                'end_time'        => $end_time->format("Y-m-d H:i:s"),
            ];
        }

        $date_data = [
            'created_at' => 20200101,
            'created_by' => 1,
            'updated_at' => 20200101,
            'updated_by' => 1,
            'deleted_at' => null,
            'deleted_by' => null
        ];

        foreach ($bulletin_board_data as $value) {
            $bulletin_board_array[] = array_merge($value, $date_data);
        }

        // 状況マスタテーブル
        DB::table('bulletin_board_data')->insert($bulletin_board_array);
    }
}
