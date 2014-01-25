<?php

use Nette\Configurator;
use Nette\Diagnostics\Debugger;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/SplClassLoader.php';

//////////

$classLoader = new SplClassLoader(null, __DIR__);
$classLoader->register();

//////////

Debugger::$strictMode = true;
Debugger::enable(!file_exists(__DIR__ . '/config/dev'), __DIR__ . '/../log');

//////////

if (!file_exists(__DIR__ . '/db/curious.sq3')) {
	copy(__DIR__ . '/db/curious-reference.sq3', __DIR__ . '/db/curious.sq3');
}

$configurator = new Configurator;
$configurator->defaultExtensions = array();

$configurator->onCompile[] = function ($configurator, $compiler) {
	$compiler->addExtension('leanmapper', new LeanMapperExtension);
};

$configurator->addConfig(__DIR__ . '/config/config.neon');
if (file_exists(__DIR__ . '/config/config.server.neon')) {
	$configurator->addConfig(__DIR__ . '/config/config.server.neon');
}
$configurator->addParameters(array(
	'appDir' => __DIR__,
	'tempDir' => __DIR__ . '/../temp',
));

$container = $configurator->createContainer();