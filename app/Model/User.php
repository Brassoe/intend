<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
	public $incrementing = false;

	public $timestamps = false;

	protected $fillable = [
		'id',
		'name',
		'email',
		'address',
		'profile_img'
	];

	protected $hidden = [
		'id'
	];

	public function modules() {
		return $this->belongsToMany('App\Model\Module')->where('user_id', '=', $this->id);
	}
}
