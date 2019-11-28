<?php

namespace App\Module;

use Laravel\Lumen\Routing\Router;

interface ModuleInterface {
	function install();
	function uninstall();
	function route(Router $router); // a method responsible for routing a request to the module's own internal routes
}
