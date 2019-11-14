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
			'id' => '123',
			'name' => Str::random(10),
			'email' => Str::random(10).'@gmail.com',
			'address' => Str::random(10).' Street '.Str::random(3),
			'profile_img' => 'images/'.Str::random(10).'.jpg'
		]);
    }
}
