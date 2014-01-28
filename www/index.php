<?php

use Nette\Diagnostics\Debugger;

require_once __DIR__ . '/../app/bootstrap.php';

try {
	$container->getByType('Curious\Fragment')->run();
} catch (Exception $e) {
	if (!Debugger::$productionMode) {
		throw $e;
	}
	$container->getByType('ErrorHandler')->handleException($e);
}

if ($container->isCreated('view')) {
	$container->getService('view')->render();
}