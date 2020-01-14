<?php

use Symfony\Component\Debug\Exception\FatalThrowableError;

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

$router->get('/', function () {
    return 'Intend API v1.0';
});

$router->post('/user', 'UserController@userCreate');

// at this point Lumen's service container doesn't seem to be fully populated, so we're stepping around and getting some values using plain ol' PHP
if(isset($_SERVER['HTTP_UID']) && ($uid = $_SERVER['HTTP_UID']) != null) {
	$router->get('/user', 'UserController@userRead');
	$router->put('/user', 'UserController@userUpdate');
	$router->delete('/user', 'UserController@userDelete');

	$router->get('/modules', 'UserController@modules');

	$router->delete('/modules/{slug}', 'ModuleController@modulesDelete');
	$router->get('/modules/{slug}', 'ModuleController@modules');
	$router->get('/catalog', 'ModuleController@catalog');
	$router->get('/catalog/install/{slug}', 'ModuleController@catalogInstall');
}

