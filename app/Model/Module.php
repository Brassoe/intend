<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Module extends Model {
	public $timestamps = false;

	protected $fillable = [
		'name',
		'display_name',
		'description',
		'color',
		'price',
		'category',
		'icon'
	];

	protected $hidden = [
		'id'
	];

	public function users() {
		return $this->belongsToMany('App\Model\User');
	}
}
