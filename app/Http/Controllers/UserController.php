<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;

class UserController extends Controller
{
	public function modules() {
		$modules = User::find($this->getId())->modules()->get();
		foreach($modules as &$module) {
			unset($module['pivot']);
			$module->images = [
				//"https://unsplash.it/256/256?random",
				"https:/placebeard.it/256/256/notag?random=".$module->name.'/image1.jpg',
				"https:/placebeard.it/256/256/notag?random=".$module->name.'/image2.jpg'
			];
		}
		return $modules;
	}

	public function userCreate(Request $request) {
		$data = $request->json()->all();
		$data['id'] = $data['uid'];
		unset($data['uid']);
		User::create($data);
	}

	public function userRead() {
		return User::find($this->getId());
	}

	public function userUpdate(Request $request) {
		$user = User::find($this->getId());

		$data = $request->json()->all();
		$data['id'] = $data['uid'];
		unset($data['uid']);

		$user->fill($data);

		$user->save();
	}

	public function userDelete() {
		User::destroy($this->getId());
	}
}
