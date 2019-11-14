<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ModulesUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('modules_users')->insert([
			'fk_module' => 1,
			'fk_user' => 'eN3cHVbcsuS7IbSda41Xm54cH4y1'
		]);
		DB::table('modules_users')->insert([
			'fk_module' => 2,
			'fk_user' => 'eN3cHVbcsuS7IbSda41Xm54cH4y1'
		]);
		DB::table('modules_users')->insert([
			'fk_module' => 3,
			'fk_user' => 'eN3cHVbcsuS7IbSda41Xm54cH4y1'
		]);
    }
}
