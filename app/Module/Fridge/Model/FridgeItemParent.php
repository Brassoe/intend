<?php

namespace App\Module\Fridge\Model;

use Illuminate\Database\Eloquent\Model;

class FridgeItemParent extends Model {
	public $timestamps = false;

	protected $fillable = [
		'name',
		'comment',
		'category_id',
		'user_id'
	];

	public function user() {
		return $this->belongsTo('App\Model\User');
	}

	public function category() {
		return $this->belongsTo(__NAMESPACE__.'\FridgeCategory');
	}

	public function children() {
		return $this->hasMany(__NAMESPACE__.'\FridgeItemChild');
	}
}

