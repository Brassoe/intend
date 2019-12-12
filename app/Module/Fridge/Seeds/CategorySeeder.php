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
			'name' => 'Drikkevarer'
		]);
		DB::table('fridge_categories')->insert([
			'name' => 'Kød'
		]);
		DB::table('fridge_categories')->insert([
			'name' => 'Fisk'
		]);
		DB::table('fridge_categories')->insert([
			'name' => 'Mejeri'
		]);
		DB::table('fridge_categories')->insert([
			'name' => 'Snacks'
		]);
		DB::table('fridge_categories')->insert([
			'name' => 'Frugt'
		]);
		DB::table('fridge_categories')->insert([
			'name' => 'Grøntsager'
		]);
		DB::table('fridge_categories')->insert([
			'name' => 'Pålæg'
		]);
		DB::table('fridge_categories')->insert([
			'name' => 'Brød'
		]);
		DB::table('fridge_categories')->insert([
			'name' => 'Dressing'
		]);
		DB::table('fridge_categories')->insert([
			'name' => 'Konserves'
		]);
    }
}
