<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
Use Faker\Factory as Faker;

class ScheduleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();
        for($i=1; $i<31; $i++)
        {
            DB::table('schedule')->insert([
                [
                    'name'                  => 'schedule#' . $i,
                    'type'                  => $faker->numberBetween(1,3),
                    'start_time'            => '00:00',
                    'end_time'              => '23:29',
                    'interval_minutes'      => 5,
                    'retry_count'          => 3,
                    'cancel_flag'           => 0,
                    'last_run_time'         => now(),
                    'created_by'            => 1
                ]
            ]);

        }

    }
}
