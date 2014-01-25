<?php

use Nette\Templating\FileTemplate;

/**
 * @author Vojtěch Kohout
 */
class CoffeeMachineRenderer
{

	/** @var FileTemplate */
	private $template;


	/**
	 * @param TemplateFactory $templateFactory
	 */
	public function __construct(TemplateFactory $templateFactory)
	{
		$this->template = $templateFactory->createTemplate('components/coffeeMachine');
	}

	/**
	 * @param CoffeeMachineComponent $coffeeMachineComponent
	 */
	public function renderCoffeeMachine(CoffeeMachineComponent $coffeeMachineComponent)
	{
		$this->template->component = $coffeeMachineComponent;
		$this->template->render();
	}

}
