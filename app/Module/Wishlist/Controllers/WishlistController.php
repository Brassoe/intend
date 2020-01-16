<?php

namespace App\Module\Wishlist\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Router;
use App\Http\Controllers\Controller;
use App\Module\ModuleInterface;
use App\Module\CaseJuggler;
use App\Model\Module;

class WishlistController extends Controller implements ModuleInterface {
	public function install() { // required by interface
		parent::installIcon();

		Module::insert([
			'name' => 'wishlist',
			'display_name' => 'Ønskeliste',
			'description' => 'Holder styr på ønskelister',
			'color' => '154, 194, 94',
			'price' => 10000,
			'category' => 'Planlægning',
			'icon' => 'mdi-gift'
		]);

		// run migrations
	}

	public function uninstall() { // required by interface
		// rollback migrations
	}

	public function route(Router $router) { // required by interface
		// register routes
		$router->group(['prefix' => '/wishlist'], function () use ($router) {
			// lists
			$router->get('/', function() {
				return 'Ønskeliste';
			});
		});
	}
}

