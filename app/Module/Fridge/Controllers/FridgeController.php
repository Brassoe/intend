<?php

namespace App\Module\Fridge\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
		// run migrations
		if(!is_dir(__DIR__.'/../../../../public/modules/fridge'))
			mkdir(__DIR__.'/../../../../public/modules/fridge', 0777, true);
		copy(__DIR__.'/../icon.svg', __DIR__.'/../../../../public/modules/fridge/icon.svg');
		Module::insert([
			'name' => 'fridge',
			'display_name' => 'Køleskab',
			'description' => 'Holder styr på køleskabet',
			'color' => '118, 202, 178',
			'price' => 6000,
			'category' => 'Husholdning',
			'icon' => 'mdi-fridge'
		]);
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
		$router->group(['prefix' => '/fridge', 'namespace' => '\\'.__NAMESPACE__], function () use ($router) {
			$router->get('/', 'FridgeController@getItems');
			$router->post('/', 'FridgeController@createItem');
			$router->put('/{id}', 'FridgeController@updateItem');
			$router->delete('/{id}', 'FridgeController@deleteItem');
			$router->get('/categories', 'FridgeController@getCategories');
		});
	}

	public function getItems() {
		$data = FridgeItemParent::with('children')->with('category')->where('user_id', '=', $this->getId())->get();
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
		$valid_parent = false;
		$data = $request->json()->all();
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
		$data['user_id'] = $this->getId();
		if(!isset($data['fridge_item_parent_id']) || $data['fridge_item_parent_id'] == null) {
			$parent = FridgeItemParent::create($data);
			$data['fridge_item_parent_id'] = $parent['id'];
			$valid_parent = true;
		}
		if(!$valid_parent && FridgeItemParent::where('id', '=', $data['fridge_item_parent_id'])->where('user_id', '=', $this->getId())->first() === null)
			return response()->json(['message' => 'Invalid parent item'], 404);
		$item = FridgeItemChild::create($data);
		$item['formatted_expiration_date'] = implode('-', array_reverse(explode('-', $item->expiration_date)));
		return $item;
	}

	public function updateItem(Request $request, $id) {
		// TODO: consider: should moves be allowed or disallowed
		// if allowed, we need more logic to potentially remove
		// parent objects, in case they become empty, when their
		// last child moves to another parent object
		$data = $request->json()->all();

		//$parent = FridgeItemParent::where('id', '=', $data['fridge_item_parent_id'])->where('user_id', '=', $this->getId())->first();
		//if($parent !== null) {
		//	$parent->fill($data);
		//	$parent->save();
		//}

		$item = FridgeItemChild::join('fridge_item_parents', 'fridge_item_children.fridge_item_parent_id', '=', 'fridge_item_parents.id')->select('fridge_item_children.*')->where('fridge_item_children.id', '=', $id)->where('user_id', '=', $this->getId())->first();
		if($item !== null) {
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
			$parent->delete(); // should cascade and delete children as well
		} else
			$item->delete();
	}

	public function getCategories() {
		return FridgeCategory::all();
	}
}

