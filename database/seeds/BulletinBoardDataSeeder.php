<?php

use Illuminate\Database\Seeder;

class BulletinBoardDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for($i=1; $i<31; $i++)
        {
            DB::table('bulletin_board_data')->insert([
                [
                    'name'                  => 'bulletin' . $i,
                    'title'                 => 'demo title ' . $i,
                    'body'                  => 'This is a sample message #' . $i,
                    'file_name'             => 'img_0'.$i.'.jpg',
                    'start_time'            => '2020-12-01 00:00:00',
                    'end_time'              => '2020-12-31 23:59:59',
                    'created_by'            => 1
                ]
            ]);

        }

    }
}
