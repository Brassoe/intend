<?php

namespace App\Module;

class CaseJuggler {
	const KEBAB = 'kebab';
	const START = 'start';
	const SNAKE = 'snake';
	const CAMEL = 'camel';

	private $pieces = [];

	private $config = [
		'kebab' => [
			'filters' => [ 'strtolower' ],
			'join' => '-'
		],
		'start' => [
			'filters' => [ 'strtolower', 'ucfirst' ],
			'join' => ''
		],
		'snake' => [
			'filters' => [ 'strtolower' ],
			'join' => '_'
		],
		'camel' => [
			'filters' => [ 'strtolower', 'ucfirst' ],
			'join' => '',
			'after' => [ 'lcfirst' ]
		]
	];

	public static function convert($input) {
		return new self(preg_split(
			'/([^a-zA-Z0-9]+|(?<=[a-z0-9])(?=[A-Z]))/',
			$input
		));
	}

	private function __construct($pieces) {
		$this->pieces = $pieces;
	}

	// convenience methods
	public function to($case) {
		return $this->doConversion($case);
	}

	private function doConversion($toCase) {
		// copy the words so we can work with them
		$result = $this->pieces;

		// get the config for the target case
		$config = $this->config[$toCase];

		// apply array pre-processing filters sequentially
		foreach($config['filters'] as $filter) {
			$result = array_map($filter, $result);
		}

		// implode to string with the specified join string
		$result = implode($config['join'], $result);

		// apply string post-processing filters sequentially
		if(isset($config['after'])) {
			foreach($config['after'] as $filter) {
				$result = array_map($filter, [$result])[0];
			}
		}

		return $result;
	}
}

