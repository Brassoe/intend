<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;

class UserController extends Controller
{
	public function modules() {
		$modules = User::find($this->getId())->modules()->where('fk_user', '=', $this->getId())->get();
		foreach($modules as &$module) {
			$module->images = [
				//"https://unsplash.it/256/256?random",
				"https:/placebeard.it/256/256/notag?random=".$module->name.'/image1.jpg',
				"https:/placebeard.it/256/256/notag?random=".$module->name.'/image2.jpg'
			];
		}
		return $modules;
	}
}
