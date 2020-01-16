<?php

namespace App\Module\Freezer\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Router;
use App\Http\Controllers\Controller;
use App\Module\ModuleInterface;
use App\Module\CaseJuggler;
use App\Model\Module;

class FreezerController extends Controller implements ModuleInterface {
	public function install() { // required by interface
		parent::installIcon();

		Module::insert([
			'name' => 'freezer',
			'display_name' => 'Fryser',
			'description' => 'Holder styr på fryseren',
			'color' => '41, 35, 92',
			'price' => 6000,
			'category' => 'Husholdning',
			'icon' => 'mdi-snowflake'
		]);

		// run migrations
	}

	public function uninstall() { // required by interface
		// rollback migrations
	}

	public function route(Router $router) { // required by interface
		// register routes
		$router->group(['prefix' => '/freezer', 'namespace' => __NAMESPACE__], function () use ($router) {
			$router->get('/', 'FreezerController@getItems');
		});
	}

	public function getItems() {
		return 'Freezer';
	}
}

