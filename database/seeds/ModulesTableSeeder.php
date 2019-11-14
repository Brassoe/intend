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
		DB::table('modules')->insert([
			'name' => 'shopping-list',
			'description' => 'Keeps a number of shopping lists',
			'price' => 2000
		]);
    }
}
