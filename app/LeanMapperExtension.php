<?php

use Nette\DI\CompilerExtension;

class LeanMapperExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig();
		$config = $config['database'];

		$useProfiler = isset($config['profiler'])
				? $config['profiler']
				: !$container->parameters['productionMode'];

		unset($config['profiler']);

		if (isset($config['flags'])) {
			$flags = 0;
			foreach ((array) $config['flags'] as $flag) {
				$flags |= constant($flag);
			}
			$config['flags'] = $flags;
		}

		$connection = $container->addDefinition($this->prefix('connection'))
				->setClass('LeanMapper\Connection', array($config));

		if ($useProfiler) {
			$panel = $container->addDefinition($this->prefix('panel'))
					->setClass('DibiNettePanel')
					->addSetup('Nette\Diagnostics\Debugger::getBar()->addPanel(?)', array('@self'))
					->addSetup('Nette\Diagnostics\Debugger::getBlueScreen()->addPanel(?)', array('DibiNettePanel::renderException'));

			$connection->addSetup('$service->onEvent[] = ?', array(array($panel, 'logEvent')));
		}
	}

}
