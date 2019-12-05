<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Module\CaseJuggler;
use App\Model\User;

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

// at this point Lumen'service container doesn't seem to be fulle populated, so we're stepping around and getting some values using plain ol' PHP
if(($uid = $_SERVER['HTTP_UID']) != null) {
	$modules = DB::table('modules')->join('module_user', 'module_id', '=', 'id')->where('user_id', '=', $uid)->pluck('name')->all();

	foreach($modules as $module) {
		$module = CaseJuggler::convert($module)->to(CaseJuggler::START);
		$controller = sprintf('App\Module\%1$s\Controllers\%1$sController', $module);
		(new $controller())->route($router);
	}
}

//$router->addRoute(
//	['HEAD','GET','POST','PUT','PATCH','DELETE','OPTIONS'],
//	'{catchall:[\w\/\-]+}',
//	function () use ($router) {
//		$segment = explode('/', $router->app->request->decodedPath(), 2)[0];
//		$segment = CaseJuggler::convert($segment)->to(CaseJuggler::START);
//		$controller = sprintf('App\Module\%1$s\Controllers\%1$sController', $segment);
//		return (new $controller())->route($router);
//	}
//);

