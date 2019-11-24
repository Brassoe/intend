<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Module extends Model {
	protected $fillable = [
		'name',
		'display_name',
		'description',
		'price',
		'icon'
	];

	protected $hidden = [
		'id'
	];
}
