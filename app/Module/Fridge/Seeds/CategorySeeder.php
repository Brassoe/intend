<?php

namespace App\Module\Fridge\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('fridge_categories')->insert([
			'name' => 'Meat'
		]);
		DB::table('fridge_categories')->insert([
			'name' => 'Veggies'
		]);
    }
}
