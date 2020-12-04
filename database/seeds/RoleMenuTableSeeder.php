<?php

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
Use Faker\Factory as Faker;

class RoleMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        try{
            DB::beginTransaction();
            $role_last = Role::select('id')->orderBy('id','desc')->first();
            for ($i = 0; $i <= 50; $i++) {
                DB::table('role')->insert([
                    'name' => $faker->company,
                    'type' => 1,
                    'created_by' => 1
                ]);
            }
            $role_last2 = Role::select('id')->orderBy('id','desc')->first();
            for ($i = $role_last->id + 1; $i <= $role_last2->id; $i++) {
                $now = date('Y-m-d H:i:s');
                DB::table('role_menu')->insert([
                    'role_id' => $i,
                    'menu_id' => $faker->numberBetween(1,11),
                    'created_by' => 1,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
            $role_last = Role::select('id')->orderBy('id','desc')->first();
            for ($i = 0; $i <= 50; $i++) {
                DB::table('role')->insert([
                    'name' => $faker->company,
                    'type' => 2,
                    'created_by' => 1
                ]);
            }
            $role_last2 = Role::select('id')->orderBy('id','desc')->first();
            for ($i = $role_last->id + 1; $i <= $role_last2->id; $i++) {
                $now = date('Y-m-d H:i:s');
                DB::table('role_menu')->insert([
                    'role_id' => $i,
                    'menu_id' => $faker->numberBetween(12,16),
                    'created_by' => 1,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        
    }
}
