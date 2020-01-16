<?php

namespace App\Module\Fridge\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Laravel\Lumen\Routing\Router;
use App\Http\Controllers\Controller;
use App\Module\ModuleInterface;
use App\Module\CaseJuggler;
use App\Model\Module;
use App\Module\Fridge\Model\FridgeCategory;
use App\Module\Fridge\Model\FridgeItemParent;
use App\Module\Fridge\Model\FridgeItemChild;
use App\Module\Fridge\Migrations\CreateCategoriesTable;
use App\Module\Fridge\Migrations\CreateItemParentTable;
use App\Module\Fridge\Migrations\CreateItemChildTable;
use App\Module\Fridge\Seeds\CategorySeeder;

class FridgeController extends Controller implements ModuleInterface {
	public function install() { // required by interface
		parent::installIcon();

		Module::insert([
			'name' => 'fridge',
			'display_name' => 'Køleskab',
			'description' => 'Holder styr på køleskabet',
			'color' => '118, 202, 178',
			'price' => 6000,
			'category' => 'Husholdning',
			'icon' => 'mdi-fridge'
		]);

		// run migrations
		(new CreateCategoriesTable())->up();
		(new CreateItemParentTable())->up();
		(new CreateItemChildTable())->up();
		(new CategorySeeder())->run();
	}

	public function uninstall() { // required by interface
		// rollback migrations
		(new CreateItemChildTable())->down();
		(new CreateItemParentTable())->down();
		(new CreateCategoriesTable())->down();
	}

	public function route(Router $router) { // required by interface
		// register routes
		$router->group(['prefix' => '/fridge', 'namespace' => __NAMESPACE__], function () use ($router) {
			$router->get('/', 'FridgeController@getItems');
			$router->post('/', 'FridgeController@createItem');
			$router->put('/{id}', 'FridgeController@updateItem');
			$router->delete('/{id}', 'FridgeController@deleteItem');
			$router->get('/categories', 'FridgeController@getCategories');
		});

		//register event listener
		Event::listen('repository.publish', sprintf('%s@handleEvent', __CLASS__));
	}

	public function handleEvent($data) {
		/**
		 * TODO: smarter logic.
		 * We need some way of looking up an item in the fridge by name.
		 * If found, we can simply add it to that item's category and fridge item parent.
		 * If not found, I don't se a way for us to determine which category we should
		 * add the item to. For now, a gross solution is to simply pick the first one.
		 * Maybe we should seed a default category as a catch-all.
		 */
		if($this->consumes($data['type'])) {
			// this is essentially FridgeCategory::getByName('[Uncategorized]') {{{
			$category = 1; // FIXME: bad assumption
			foreach($this->getCategories() as $item) {
				if($item->name == '[Uncategorized]')
					$category = $item->id;
			}
			// }}}
			$this->createItemActual([
				'name' => $data['data']['name'],
				'category_id' => $category,
				'fridge_item_parent_id' => null
			]);
		}
	}

	public function getItems() {
		$data = FridgeItemParent::with('children')->with('category')->where('user_id', $this->getId())->get();
		// post-process the data a little
		foreach($data as $key => $item) {
			// pull the category name up into the object itself, rather than having to bury into a sub-array to find it
			$data[$key]['category_name'] = $item->category->name;
			$item->children->map(function($child) {
				// add a computed field with the date in smallest-to-largest-unit order
				$child['formatted_expiration_date'] = implode('-', array_reverse(explode('-', $child->expiration_date)));
				return $child;
			});
		}
		return $data;
	}

	public function createItem(Request $request) {
		/*
		 * TODO: need to create FridgeItemParent if none exists
		 * sub-par solution for now: if fridge_item_parent_id == null,
		 * create the parent object
		 * ... sub-par because it allows API users to leave out the parent
		 * object ID and thus create lots and lots of effectively identical
		 * parent objects
		 * ... a more "par" solution would be to check the name against the
		 * database. TODO: requires the combination of the (name, user_id)
		 * fields to be unique
		 */
		return $this->createItemActual($request->json()->all());
	}

	private function createItemActual($data) {
		$valid_parent = false;
		$data['user_id'] = $this->getId();

		if(!isset($data['fridge_item_parent_id']) || $data['fridge_item_parent_id'] == null) {
			$parent = FridgeItemParent::create($data);
			$data['fridge_item_parent_id'] = $parent['id'];
			$valid_parent = true;
		}

		if(!$valid_parent && FridgeItemParent::where('id', $data['fridge_item_parent_id'])->where('user_id', $this->getId())->first() === null)
			return response()->json(['message' => 'Invalid parent item'], 404);

		$item = FridgeItemChild::create($data);
		$item['formatted_expiration_date'] = implode('-', array_reverse(explode('-', $item->expiration_date)));

		return $item;
	}

	public function updateItem(Request $request, $id) {
		$data = $request->json()->all();

		// disallow moving item to another parent (doesn't make sense for any of our use cases)
		unset($data['fridge_item_parent_id']);

		$item = FridgeItemChild::join('fridge_item_parents', 'fridge_item_children.fridge_item_parent_id', '=', 'fridge_item_parents.id')->select('fridge_item_children.*')->where('fridge_item_children.id', $id)->where('user_id', $this->getId())->first();
		if($item !== null) {
			// propagate data to item parent
			$item->fridgeitemparent->fill($data);
			$item->fridgeitemparent->save();
			// update item child
			$item->fill($data);
			$item->save();

			$item['formatted_expiration_date'] = implode('-', array_reverse(explode('-', $item->expiration_date)));
			return $item;
		} else
			return response()->json(['message' => 'Item not found'], 404);
	}

	public function deleteItem($id) {
		// TODO: if this is the last item, remove the parent object as well
		$item = FridgeItemChild::find($id);
		$parent = FridgeItemParent::with('children')->find($item['fridge_item_parent_id']);
		if(count($parent['children']) <= 1) {
			$parent->delete(); // cascades, deleting the child as well
		} else
			$item->delete();
	}

	public function getCategories() {
		return FridgeCategory::all();
	}
}
