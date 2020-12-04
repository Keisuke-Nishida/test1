<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageTableSeeder extends Seeder
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
            DB::table('message')->insert([
                [
                    'name'                  => 'demomessage' . $i,
                    'key'                 => 'DEMO_MESSAGE_' . $i,
                    'value'                  => 'Demo message #' . $i,
                    'created_by'            => 1
                ]
            ]);

        }

    }
}
