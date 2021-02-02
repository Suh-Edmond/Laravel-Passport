<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class ProductConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        DB::table('product_conditions')->insert([
            'condition' => "Excellent"
        ]);
        DB::table('product_conditions')->insert([
            'condition' => "Good",
        ]);
        DB::table('product_conditions')->insert([
            'condition' => "In Used",
        ]);
        DB::table('product_conditions')->insert([
            'condition' => "Robust",
        ]);
        DB::table('product_conditions')->insert([
            'condition' => "Weak Product",
        ]);
    }
}
