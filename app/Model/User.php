<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
	protected $fillable = [
		'id',
		'name',
		'email',
		'address'
	];

	protected $hidden = [
		'id'
	];

	protected $with = [
		'modules'
	];

	public function modules() {
		return $this->belongsToMany('App\Model\Module', 'modules_users', 'fk_user', 'fk_module');
	}
}
