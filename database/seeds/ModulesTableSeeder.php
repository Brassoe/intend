<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//DB::table('modules')->insert([
		//	'name' => 'shopping-list',
		//	'display_name' => 'Indkøbsliste',
		//	'description' => 'Holder styr på indkøbslisten',
		//	'price' => 2000,
		//	'category' => 'Planlægning',
		//	'icon' => 'mdi-chemical-weapon'
		//]);
		//DB::table('modules')->insert([
		//	'name' => 'meal-plan',
		//	'display_name' => 'Madplan',
		//	'description' => 'Holder styr på madplanen',
		//	'price' => 4000,
		//	'category' => 'Planlægning',
		//	'icon' => 'mdi-food'
		//]);
		//DB::table('modules')->insert([
		//	'name' => 'fridge',
		//	'display_name' => 'Køleskab',
		//	'description' => 'Holder styr på køleskabet',
		//	'price' => 6000,
		//	'category' => 'Husholdning',
		//	'icon' => 'mdi-fridge'
		//]);
		//DB::table('modules')->insert([
		//	'name' => 'offers',
		//	'display_name' => 'Tilbud',
		//	'description' => 'Holder styr på tilbud',
		//	'price' => 8000,
		//	'category' => 'Indkøb',
		//	'icon' => 'mdi-shopping'
		//]);
    }
}
