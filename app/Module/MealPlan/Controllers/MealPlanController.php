<?php

namespace App\Module\MealPlan\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Router;
use App\Http\Controllers\Controller;
use App\Module\ModuleInterface;
use App\Module\CaseJuggler;
use App\Model\Module;

class MealPlanController extends Controller implements ModuleInterface {
	public function install() { // required by interface
		// run migrations
		if(!is_dir(__DIR__.'/../../../../public/modules/meal-plan'))
			mkdir(__DIR__.'/../../../../public/modules/meal-plan', 0777, true);
		copy(__DIR__.'/../icon.svg', __DIR__.'/../../../../public/modules/meal-plan/icon.svg');
		Module::insert([
			'name' => 'meal-plan',
			'display_name' => 'Madplan',
			'description' => 'Holder styr på madplanen',
			'color' => '#e76884',
			'price' => 4000,
			'category' => 'Planlægning',
			'icon' => 'mdi-food'
		]);
	}

	public function uninstall() { // required by interface
		// rollback migrations
	}

	public function route(Router $router) { // required by interface
		// register routes
		$router->group(['prefix' => '/meal-plan'], function () use ($router) {
			// lists
			$router->get('/', function() {
				return 'Madplan';
			});
		});
	}
}

