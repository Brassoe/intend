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

$router->get('/test', 'UserController@modules');

$router->get('/catalog', 'ModuleController@catalog');

$router->get('/catalog/install/{slug}', 'ModuleController@catalogInstall');

$router->post('/user/create', 'UserController@userCreate');

$router->get('/user', 'UserController@userRead');

$router->put('/user', 'UserController@userUpdate');

$router->delete('/user', 'UserController@userDelete');

$router->delete('/modules/delete/{slug}', 'ModuleController@modulesDelete');

$router->get('/modules', 'UserController@modules');

$router->get('/modules/{slug}', 'ModuleController@modules');

$router->get('/module/{slug}/{action}', function ($slug, $action) use ($router) {
	// TODO: this is the part that modules should be able to register
});
