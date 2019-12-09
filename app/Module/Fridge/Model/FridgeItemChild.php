<?php

namespace App\Module\Fridge\Model;

use Illuminate\Database\Eloquent\Model;

class FridgeItemChild extends Model {
	public $timestamps = false;

	protected $fillable = [
		'expiration_date',
		'fridge_item_parent_id'
	];

	public function parentItem() {
		return $this->belongsTo(__NAMESPACE__.'\FridgeItemParent');
	}
}
