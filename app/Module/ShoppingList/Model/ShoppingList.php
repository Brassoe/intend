<?php

namespace App\Model\ShoppingList;

use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model {
	public $timestamps = false;

	protected $fillable = [
		'name',
		'color',
		'user_id'
	];
}

