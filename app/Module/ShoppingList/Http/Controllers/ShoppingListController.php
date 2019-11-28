<?php

namespace App\Module\ShoppingList\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Router;
use App\Http\Controllers\Controller;
use App\Module\ModuleInterface;
use App\Module\CaseJuggler;
use App\Module\ShoppingList\Model\ShoppingList; // the added "Model" here is because PHP reserves the word "list", even in proper case, apparently
use App\Module\ShoppingList\Model\ShoppingListItem;
use App\Module\ShoppingList\Migrations\CreateListsTable;
use App\Module\ShoppingList\Migrations\CreateItemsTable;
use App\Model\Module;

class ShoppingListController extends Controller implements ModuleInterface {
	public function install() { // required by interface
		// run migrations
		Module::insert([
			'name' => 'shopping-list',
			'display_name' => 'Indkøbsliste',
			'description' => 'Holder styr på indkøbslisten',
			'price' => 2000,
			'category' => 'Planlægning',
			'icon' => 'mdi-chemical-weapon'
		]);
		(new CreateListsTable())->up();
		(new CreateItemsTable())->up();
	}

	public function uninstall() { // required by interface
		// rollback migrations
		(new CreateItemsTable())->down();
		(new CreateListsTable())->down();
	}

	public function route(Router $router) { // required by interface
		// check if module is installed in DB
		if(Module::where('name', '=', 'shopping-list')->first() === null) {
			// ... if not, install it
			$this->install();
		}

		// register routes
		$router->group(['prefix' => '/shopping-list'], function () use ($router) {
			// lists
			$router->post('/', __NAMESPACE__.'\ShoppingListController@createList');
			$router->get('/', __NAMESPACE__.'\ShoppingListController@getAllLists');
			$router->delete('/{id}', __NAMESPACE__.'\ShoppingListController@deleteList');

			// items
			$router->post('items', __NAMESPACE__.'\ShoppingListController@createItem');
			$router->put('items', __NAMESPACE__.'\ShoppingListController@flipCheck');
			$router->delete('items/{id}', __NAMESPACE__.'\ShoppingListController@deleteItem');
		});

		// re-dispatch request (which routes it to the correct endpoint)
		return $router->app->dispatch($router->app->request);
	}

	public function createList() {
		// TODO
	}

	public function getAllLists() {
		// TODO
		return $this->getId();
	}

	public function deleteList($id) {
		// TODO
	}

	public function createItem() {
		// TODO
		return 'hey you';
	}

	public function flipCheck() {
		// TODO
		// not sure if SQL will let us pull the xor boolean flipping trick (current value ^= true) (a cursory web search says no)
	}

	public function deleteItem($id) {
		// TODO
	}
}
