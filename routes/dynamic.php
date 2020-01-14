<?php

use Illuminate\Support\Facades\DB;
use App\Module\CaseJuggler;
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

// at this point Lumen's service container doesn't seem to be fully populated, so we're stepping around and getting some values using plain ol' PHP
if(isset($_SERVER['HTTP_UID']) && ($uid = $_SERVER['HTTP_UID']) != null) {
	$modules = DB::table('modules')->join('module_user', 'module_id', '=', 'id')->where('user_id', $uid)->pluck('name')->all();

	foreach($modules as $module) {
		$module = CaseJuggler::convert($module)->to(CaseJuggler::START);
		$controller = sprintf('App\Module\%1$s\Controllers\%1$sController', $module);
		(new $controller())->route($router);
	}
}

