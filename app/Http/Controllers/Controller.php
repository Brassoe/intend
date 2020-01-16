<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Module\CaseJuggler;

class Controller extends BaseController
{
	private $manifest;

	public function __construct() {
		if(preg_match('#^App\\\\Module\\\\(.*?)\\\\#', static::class, $match) === 1) {
			// TODO: read manifest into read-only private variable
			$this->manifest = json_decode(file_get_contents(sprintf('%s/app/Module/%s/manifest.json', $_ENV['PWD'], $match[1])), true);
		}
	}

	protected function getId() {
		return app(Request::class)->headers->get('uid');
	}

	final protected function getManifest($key = null) {
		return $key !== null ? $this->manifest[$key] : $this->manifest;
	}

	// convenience method
	final protected function consumes($type) {
		return in_array($type, $this->getManifest('consumes'));
	}

	private function getCallingClass() {
		// step back a stack frame to find the calling class
		$bt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
		// get the fully qualified class name of the class that made the call to this parent class
		$class = get_class($bt[0]['object']);

		// make sure the calling class resides in the App\Module namespace
		if(preg_match('#^App\\\\Module\\\\(.*?)\\\\#', $class, $match) === 1)
			// if so, return the found name of the module
			return $match[1];
		return false;
	}

	protected function installIcon() {
		// make sure the calling class is a module
		if(($caller = $this->getCallingClass()) !== false) {
			// convert module name to kebab case for later use
			$kebabCaller = CaseJuggler::convert($caller)->to(CaseJuggler::KEBAB);
			// get the root folder
			$cwd = $_ENV['PWD'];
			// create a publicly accessible path unique to the module
			$publicFolder = sprintf('%s/public/modules/%s', $cwd, $kebabCaller);

			// create the folder if it doesn't exist yet
			if(!is_dir($publicFolder))
				mkdir($publicFolder, 0777, true);

			// copy the icon from the module's folder to the public folder
			copy(sprintf('%s/app/Module/%s/icon.svg', $cwd, $caller), sprintf('%s/icon.svg', $publicFolder));
		}
	}
}
