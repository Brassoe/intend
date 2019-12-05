<?php

namespace App;

$app = require __DIR__.'/../bootstrap/app.php';
$app->run();
echo PHP_EOL;

use App\Module\CaseJuggler;
use App\Model\Module;

class Install {
	public function run() {
		$modules = Module::all()->pluck('name')->all();
		foreach(glob(__DIR__.'/Module/**/manifest.json') as $manifest) {
			$name = json_decode(file_get_contents($manifest), true)['name'];
			if(!in_array($name, $modules)) {
				$name = CaseJuggler::convert($name)->to(CaseJuggler::START);
				echo "Installing $name\n";
				$class = 'App\Module\\'.$name.'\Controllers\\'.$name.'Controller';
				(new $class())->install();
			}
		}
	}
}

(new Install())->run();
