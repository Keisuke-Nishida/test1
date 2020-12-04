<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserTableSeeder::class,
            CustomerTableSeeder::class,
            ConditionTableSeeder::class,
            PrefectureTableSeeder::class,
            TransportTableSeeder::class,
            VoucherTableSeeder::class,
            SampleTableSeeder::class
        ]);
    }
}
