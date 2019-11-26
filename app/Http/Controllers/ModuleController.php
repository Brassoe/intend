<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Model\Module;

class ModuleController extends Controller
{
	public function modules($slug) {
		$module = Module::where('name', '=', $slug)->first();
		$module->installed = !!count($module->users()->where('user_id', '=', $this->getId())->get());
		return $module;
	}

	public function modulesDelete($slug) {
		// TODO
		$module = Module::where('name', '=', $slug)->first();

		$module->users()->where('user_id', '=', $this->getId())->detach();
	}

	public function catalog() {
		$modules = Module::whereDoesntHave('users', function(Builder $query) {
			$query->where('user_id', '=', $this->getId());
		})->get();

		foreach($modules as &$module) {
			$module->images = [
				//"https://unsplash.it/256/256?random",
				"https:/placebeard.it/256/256/notag?random=".$module->name.'/image1.jpg',
				"https:/placebeard.it/256/256/notag?random=".$module->name.'/image2.jpg'
			];
		}

		return $modules;
	}

	public function catalogInstall($slug) {
		$module = Module::where('name', '=', $slug)->first();

		$module->users()->where('user_id', '=', $this->getId())->attach($this->getId());
	}
}
