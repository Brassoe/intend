<?php

namespace App\Module\Fridge\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Router;
use App\Http\Controllers\Controller;
use App\Module\ModuleInterface;
use App\Module\CaseJuggler;
use App\Model\Module;

class FridgeController extends Controller implements ModuleInterface {
	public function install() { // required by interface
		// run migrations
		Module::insert([
			'name' => 'fridge',
			'display_name' => 'Køleskab',
			'description' => 'Holder styr på køleskabet',
			'price' => 6000,
			'category' => 'Husholdning',
			'icon' => 'mdi-fridge'
		]);
	}

	public function uninstall() { // required by interface
		// rollback migrations
	}

	public function route(Router $router) { // required by interface
		// register routes
		$router->group(['prefix' => '/fridge'], function () use ($router) {
			// lists
			$router->get('/', function() {
				return 'Køleskab';
			});
		});
	}
}

