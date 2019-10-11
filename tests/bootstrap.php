<?php

// The Nette Tester command-line runner can be
// invoked through the command: ../vendor/bin/tester .

if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer install`';
	exit(1);
}


// configure environment
Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');


// configure locks dir
define('LOCKS_DIR', __DIR__ . '/locks');
@mkdir(LOCKS_DIR);

require_once __DIR__ . '/../src/Mesour/Enum/Enum.php';
require_once __DIR__ . '/../src/Mesour/Enum/exceptions/InvalidArgumentException.php';
require_once __DIR__ . '/../src/Mesour/Enum/exceptions/InvalidStateException.php';
require_once __DIR__ . '/../src/Mesour/Enum/exceptions/InvalidEnumValueException.php';

function run(Tester\TestCase $testCase)
{
	$testCase->run(isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : NULL);
}
