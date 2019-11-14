<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ModuleFilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('module_files')->insert([
			'path' => 'Http/Controllers/ShoppingListController.php',
			'fk_module' => 1
		]);
    }
}
