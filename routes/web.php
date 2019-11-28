<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Module\CaseJuggler;

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

$router->post('/user', 'UserController@userCreate');
$router->get('/user', 'UserController@userRead');
$router->put('/user', 'UserController@userUpdate');
$router->delete('/user', 'UserController@userDelete');

$router->get('/modules', 'UserController@modules');

$router->delete('/modules/{slug}', 'ModuleController@modulesDelete');
$router->get('/modules/{slug}', 'ModuleController@modules');
$router->get('/catalog', 'ModuleController@catalog');
$router->get('/catalog/install/{slug}', 'ModuleController@catalogInstall');

$router->addRoute(
	['HEAD','GET','POST','PUT','PATCH','DELETE','OPTIONS'],
	'{catchall:[\w\/\-]+}',
	function () use ($router) {
		$segment = explode('/', $router->app->request->decodedPath(), 2)[0];
		$segment = CaseJuggler::convert($segment)->to(CaseJuggler::START);
		$controller = sprintf('App\Module\%1$s\Http\Controllers\%1$sController', $segment);
		return (new $controller())->route($router);
		// TODO catch exceptions
	}
);

