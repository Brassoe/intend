<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Module extends Model {
	public $timestamps = false;

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

	public function users() {
		return $this->belongsToMany('App\Model\User');
	}
}
