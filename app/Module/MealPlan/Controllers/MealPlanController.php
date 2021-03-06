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
		parent::installIcon();

		Module::insert([
			'name' => 'meal-plan',
			'display_name' => 'Madplan',
			'description' => 'Holder styr på madplanen',
			'color' => '231, 104, 132',
			'price' => 4000,
			'category' => 'Planlægning',
			'icon' => 'mdi-food'
		]);

		// run migrations
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

