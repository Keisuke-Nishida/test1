<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
Use Faker\Factory as Faker;
use Illuminate\Support\Arr;

class NoticeDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        $faker = Faker::create();
        $now = date('Y-m-d H:i:s');
        try{
            DB::beginTransaction();
            for ($i = 0; $i <= 99; $i++) {
                $rand_start = $faker->numberBetween(1,20);
                $rand_end = $faker->numberBetween(1,20);
                $start_time = date( 'Y-m-d H:i:s', strtotime("$now +$rand_start day") );
                $end_time = date( 'Y-m-d H:i:s', strtotime("$start_time +$rand_end day") );
                DB::table('notice_data')->insert([
                    'name' => $faker->word,
                    'title' => $faker->name,
                    'body' => $faker->sentence(5),
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'created_at' => $now,
                    'created_by' => 1
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
