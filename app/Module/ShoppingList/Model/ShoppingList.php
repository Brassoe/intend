<?php

namespace App\Module\ShoppingList\Model;

use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model {
	public $timestamps = false;

	protected $fillable = [
		'name',
		'color',
		'user_id'
	];

	public function user() {
		return $this->belongsTo('App\Model\User');
	}

	public function items() {
		return $this->hasMany(__NAMESPACE__.'\ShoppingListItem');
	}
}

