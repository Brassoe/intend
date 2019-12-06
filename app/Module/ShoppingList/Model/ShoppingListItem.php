<?php

namespace App\Module\ShoppingList\Model;

use Illuminate\Database\Eloquent\Model;

class ShoppingListItem extends Model {
	public $timestamps = false;

	protected $fillable = [
		'name',
		'checked',
		'shopping_list_id'
	];

	public function shoppingList() {
		return $this->belongsTo(__NAMESPACE__.'\ShoppingList');
	}
}
