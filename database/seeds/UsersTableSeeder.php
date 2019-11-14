<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('users')->insert([
			'id' => 'eN3cHVbcsuS7IbSda41Xm54cH4y1',
			'name' => 'Test',
			'email' => 'test@test.dk',
			'address' => 'Testgade 10, Testby',
			'profile_img' => 'https://drawcentral.com/wp-content/uploads/2012/01/gir_invader_zim-150x150.jpg'
		]);
    }
}
