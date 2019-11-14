<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ModuleImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('module_images')->insert([
			'path' => 'images/shopping-list/example1.png',
			'fk_module' => 1
		]);
		DB::table('module_images')->insert([
			'path' => 'images/shopping-list/example2.png',
			'fk_module' => 1
		]);
    }
}
