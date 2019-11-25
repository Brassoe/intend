<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
	protected function getId() {
		return app(Request::class)->headers->get('uid');
	}
}
