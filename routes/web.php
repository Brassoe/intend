<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

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

$router->get('/modules/{id}', function ($id) use ($router) {
	$result = DB::table('modules')->find($id);
	return new JsonResponse($result);
});

$router->get('/users/{id}', function ($id) use ($router) {
	$result = DB::table('users')->find($id);
	return new JsonResponse($result);
});
