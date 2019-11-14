<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/hello', function () use ($router) {
    return 'hello world';
});

$router->get('/db', function () use ($router) {
    return app('db')->table('migrations')->get();
});

$router->get('/modules', function () use ($router) {
	$result = DB::table('modules')->get();
	foreach($result as &$item) {
		$item->images = [
			"https:/placebeard.it/256/256/notag?random=".Str::random(3),
			"https:/placebeard.it/256/256/notag?random=".Str::random(3)
		];
	}
	return $result;
});

$router->get('/modules/{id}', function ($id) use ($router) {
	$result = DB::table('modules')->find($id);

	if(!empty($result)) {
		$result->images = [
			//"https://unsplash.it/256/256?random",
			"https:/placebeard.it/256/256/notag?random=".Str::random(3),
			"https:/placebeard.it/256/256/notag?random=".Str::random(3)
		];
	}

	return new JsonResponse($result);
});

$router->get('/users/{id}', function ($id) use ($router) {
	$result = DB::table('users')->find($id);

	if(!empty($result)) {
		$result->profile_img = "https://placebeard.it/256/256/notag?random=".Str::random(3);
	}

	return new JsonResponse($result);
});
