<?php

namespace App\Model\ShoppingList;

use Illuminate\Database\Eloquent\Model;

class ShoppingListItem extends Model {
	public $timestamps = false;

	protected $fillable = [
		'name',
		'checked',
		'list_id'
	];
}
