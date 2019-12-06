<?php

namespace App\Module\Offers\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Router;
use App\Http\Controllers\Controller;
use App\Module\ModuleInterface;
use App\Module\CaseJuggler;
use App\Model\Module;

class OffersController extends Controller implements ModuleInterface {
	public function install() { // required by interface
		// run migrations
		if(!is_dir(__DIR__.'/../../../../public/modules/offers'))
			mkdir(__DIR__.'/../../../../public/modules/offers', 0777, true);
		copy(__DIR__.'/../icon.svg', __DIR__.'/../../../../public/modules/offers/icon.svg');
		Module::insert([
			'name' => 'offers',
			'display_name' => 'Tilbud',
			'description' => 'Holder styr på tilbud',
			'price' => 8000,
			'category' => 'Planlægning',
			'icon' => 'mdi-shopping'
		]);
	}

	public function uninstall() { // required by interface
		// rollback migrations
	}

	public function route(Router $router) { // required by interface
		// register routes
		$router->group(['prefix' => '/offers'], function () use ($router) {
			// lists
			$router->get('/', function() {
				return 'Offers';
			});
		});
	}
}

