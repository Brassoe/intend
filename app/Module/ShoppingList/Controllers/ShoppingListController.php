<?php

namespace App\Module\ShoppingList\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Router;
use App\Http\Controllers\Controller;
use App\Module\ModuleInterface;
use App\Module\CaseJuggler;
use App\Module\ShoppingList\Model\ShoppingList;
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
		// register routes
		$router->group(['prefix' => '/shopping-list'], function () use ($router) {
			// lists
			$router->post('/', '\\'.__NAMESPACE__.'\ShoppingListController@createList');
			$router->get('/', '\\'.__NAMESPACE__.'\ShoppingListController@getAllLists');
			$router->get('/icon', '\\'.__NAMESPACE__.'\ShoppingListController@icon');
			$router->delete('/{id}', '\\'.__NAMESPACE__.'\ShoppingListController@deleteList');

			// items
			$router->post('items', '\\'.__NAMESPACE__.'\ShoppingListController@createItem');
			$router->put('items/{id}', '\\'.__NAMESPACE__.'\ShoppingListController@flipCheck');
			$router->delete('items/{id}', '\\'.__NAMESPACE__.'\ShoppingListController@deleteItem');
		});
	}

	public function createList(Request $request) {
		$data = $request->json()->all();

		$data['color'] = null;
		$data['user_id'] = $this->getId();

		return response()->json(ShoppingList::create($data));
	}

	public function getAllLists() {
		return ShoppingList::with('user')->with('items')->where('user_id', '=', $this->getId())->get();
	}

	public function deleteList($id) {
		// TODO: possibly return a "fail" response if no list with the given ID exists
		return ShoppingList::where('user_id', '=', $this->getId())->where('id', '=', $id)->delete();
	}

	public function createItem(Request $request) {
		$data = $request->json()->all();

		return response()->json([ShoppingListItem::insert($data)]);
	}

	public function flipCheck($id) {
		$item = ShoppingListItem::with('shoppingList')->where('user_id', '=', $this->getId())->where('id', '=', $id)->first();
		$item['checked'] ^= true;
		$item->save();
		// not sure if SQL will let us pull the xor boolean flipping trick (current value ^= true) (a cursory web search says no)
	}

	public function deleteItem($id) {
		return ShoppingListItem::with('shoppingList')->where('user_id', '=', $this->getId())->where('id', '=', $id)->delete();
	}
}
