<?php

use Curious\Components;
use Curious\State;
use Nette\Templating\FileTemplate;

/**
 * @fragment coffee
 * @following initNavigation
 */
function renderCoffee(Components $components, FileTemplate $template, Redirector $redirector, CoffeeMachineRenderer $coffeeMachineRenderer)
{
	$components->register(
		'coffeeMachine',
		$template->coffeeMachine = new CoffeeMachineComponent($redirector, $coffeeMachineRenderer)
	);
}

/**
 * @fragment coffees
 * @following initNavigation
 * @usingState machinesCount,{^coffeeMachine[12]$}
 */
function renderCoffees(State $state, Components $components, FileTemplate $template, Redirector $redirector, CoffeeMachineRenderer $coffeeMachineRenderer)
{
	$template->resetParameters = array('machinesCount' => null);
	$machinesCount = $state->get('machinesCount', 1);

	$template->coffeeMachines = array();
	for ($i = 1; $i <= $machinesCount; $i++) {
		$name = 'coffeeMachine' . $i;
		$components->register($name, $coffeeMachine = new CoffeeMachineComponent($redirector, $coffeeMachineRenderer));
		$template->coffeeMachines[$name] = $coffeeMachine;
		$template->resetParameters[$name] = null;
	}
}