<?php

namespace App\Module\Fridge\Model;

use Illuminate\Database\Eloquent\Model;

class FridgeCategory extends Model {
	public $timestamps = false;

	protected $fillable = [
		'name',
		'color'
	];

	public function parentItems() {
		return $this->hasMany(__NAMESPACE__.'\FridgeItemParent');
	}
}
