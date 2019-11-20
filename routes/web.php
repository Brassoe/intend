<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Laravel\Lumen\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// default route
$router->get('/', function () use ($router) {
    return $router->app->version();
});

// catalog (excluding modules already installed by user)
$router->get('/catalog', function () use ($router) {
	// pluck list of module IDs belonging to user
	$ids = DB::table('modules_users')->where('fk_user', '=', $_SERVER['HTTP_UID'])->pluck('fk_module');

	// get list of modules not belonging to the user
	$result = DB::table('modules')->whereNotIn('id', $ids->all())->get();

	// add dummy images for test purposes - TODO: remove
	foreach($result as &$item) {
		$item->images = [
			"https:/placebeard.it/256/256/notag?random=".Str::random(3),
			"https:/placebeard.it/256/256/notag?random=".Str::random(3)
		];
	}

	return $result;
});

$router->get('/catalog/install/{slug}', function ($slug) use ($router) {
	// TODO: should associate the given module with the user
	$module = DB::table('modules')->where('name', '=', $slug)->first();

	if($module === null) {
		return new JsonResponse(false, 404);
	}

	try {
		$result = DB::table('modules_users')->insert([
			'fk_module' => $module->id,
			'fk_user' => $_SERVER['HTTP_UID']
		]);
	} catch (PDOException $e) {
		$result = false;
	}

	return new JsonResponse($module, $result ? 200 : 404);
});

$router->get('/module/{slug}/{action}', function ($slug, $action) use ($router) {
	// TODO: this is the part that modules should be able to register
});

$router->delete('/modules/delete/{slug}', function ($slug) use ($router) {
	// TODO: delete the association between the given module and the user
	$module = DB::table('modules')->where('name', '=', $slug)->first();

	if($module === null) {
		return new JsonResponse(false, 404);
	}

	$result = !!DB::table('modules_users')->where('fk_module', '=', $module->id)->where('fk_user', '=', $_SERVER['HTTP_UID'])->delete();

	return new JsonResponse($result, $result ? 200 : 404);
});

$router->post('/user/create', function (Request $request) use ($router) {
	// TODO: validate and create the user (this should probably be a POST request)
	// Required fields:
	//	id
	//	name
	//	email
	//	address
	$data = $request->json()->all();
	$data['id'] = $data['uid'];
	unset($data['uid']);
	try {
		$result = !!DB::table('users')->insert($data);
	} catch (PDOException $e) {
		$result = false;
	}
	return new JsonResponse($result, $result ? 200 : 404);
});

$router->delete('/user/delete', function () use ($router) {
	// TODO: delete the user (none of this new-fangled CRU, we go full CRUD)
	// TODO: check that this actually works
	$result = DB::table('users')->delete($_SERVER['HTTP_UID']) > 0;

	return new JsonResponse($result, $result ? 200 : 404);
});

$router->put('/user/update', function () use ($router) {
	// TODO: update the user (this should probably be a PUT request)
});

$router->get('/user', function () use ($router) {
	return new JsonResponse(DB::table('users')->find($_SERVER['HTTP_UID']));
});

// user's modules
$router->get('/modules', function () use ($router) {
	// get list of modules belonging to user
	$result = DB::table('modules')->join('modules_users', 'modules.id', '=', 'modules_users.fk_module')->where('modules_users.fk_user', '=', $_SERVER['HTTP_UID'])->get();

	// add dummy images for test purposes - TODO: remove
	foreach($result as &$item) {
		$item->images = [
			"https:/placebeard.it/256/256/notag?random=".Str::random(3),
			"https:/placebeard.it/256/256/notag?random=".Str::random(3)
		];
	}
	return $result;
});

$router->get('/modules/{slug}', function ($slug) use ($router) {
	// find specific module
	$result = DB::table('modules')->where('name', '=', $slug)->first();
	$id = $result->id;

	// add dummy images for test purposes - TODO: remove
	if(!empty($result)) {
		$result->images = [
			//"https://unsplash.it/256/256?random",
			"https:/placebeard.it/256/256/notag?random=".Str::random(3),
			"https:/placebeard.it/256/256/notag?random=".Str::random(3)
		];

		// add a field describing whether the user has this module installed or not
		$result->installed = !!DB::table('modules_users')->where('fk_user', '=', $_SERVER['HTTP_UID'])->where('fk_module', '=', $id)->selectRaw('IF(COUNT(*) > 0, 1, 0) AS installed')->first()->installed;
	}

	return new JsonResponse($result);
});
