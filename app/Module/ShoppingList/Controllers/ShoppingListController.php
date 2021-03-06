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
		parent::installIcon();

		Module::insert([
			'name' => 'shopping-list',
			'display_name' => 'Indkøbsliste',
			'description' => 'Holder styr på indkøbslisten',
			'color' => '228, 168, 83',
			'price' => 2000,
			'category' => 'Planlægning',
			'icon' => 'mdi-chemical-weapon'
		]);

		// run migrations
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
		$router->group(['prefix' => '/shopping-list', 'namespace' => __NAMESPACE__], function () use ($router) {
			// lists
			$router->post('/', 'ShoppingListController@createList');
			$router->get('/', 'ShoppingListController@getAllLists');
			$router->put('/{id}', 'ShoppingListController@updateColor');
			$router->delete('/{id}', 'ShoppingListController@deleteList');

			// items
			$router->post('items', 'ShoppingListController@createItem');
			$router->put('items/{id}', 'ShoppingListController@flipCheck');
			$router->delete('items/{id}', 'ShoppingListController@deleteItem');
		});
	}

	public function createList(Request $request) {
		$data = $request->json()->all();

		$data['color'] = null;
		$data['user_id'] = $this->getId();

		return response()->json(ShoppingList::create($data));
	}

	public function getAllLists() {
		return ShoppingList::with('user')->with('items')->where('user_id', $this->getId())->get();
	}

	public function updateColor(Request $request, $id) {
		$data = $request->json()->all();
		$list = ShoppingList::with('user')->with('items')->where('user_id', $this->getId())->where('id', $id)->first();

		if($list !== null && isset($data['color'])) {
			$list->color = $data['color'];
			$list->save();
		} else
			return response()->json(['message' => 'List not found'], 404);
	}

	public function deleteList($id) {
		// TODO: possibly return a "fail" response if no list with the given ID exists
		return ShoppingList::where('user_id', $this->getId())->where('id', $id)->delete();
	}

	public function createItem(Request $request) {
		$data = $request->json()->all();

		return response()->json(ShoppingListItem::create($data));
	}

	public function flipCheck($id) {
		$item = ShoppingListItem::with('shoppingList')
			->join('shopping_lists', 'shopping_list_id', '=', 'shopping_lists.id')
			->where('user_id', $this->getId())
			->where('shopping_list_items.id', $id)
			->select(['shopping_list_items.*'])
			->first();

		if($item !== null) {
			$item->checked ^= true;
			$item->save();
		} else
			return response()->json(['message' => 'Item not found'], 404);
	}

	public function deleteItem($id) {
		return ShoppingListItem::with('shoppingList')
			->join('shopping_lists', 'shopping_list_id', '=', 'shopping_lists.id')
			->where('user_id', $this->getId())
			->where('shopping_list_items.id', $id)
			->delete();
	}
}
