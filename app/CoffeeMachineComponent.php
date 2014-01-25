<?php

use Curious\Component;
use Model\CoffeeMachine;
use Nette\Templating\Template;

/**
 * @author Vojtěch Kohout
 */
class CoffeeMachineComponent extends Component
{

	const DEFAULT_COIN = 5;

	/** @var array */
	protected $defaults = array(
		'money' => '0'
	);

	/** @var Redirector */
	private $redirector;

	/** @var CoffeeMachine */
	private $coffeeMachine;

	/** @var CoffeeMachineRenderer */
	private $renderer;


	/**
	 * @param Redirector $redirector
	 * @param CoffeeMachineRenderer $renderer
	 */
	public function __construct(Redirector $redirector, CoffeeMachineRenderer $renderer)
	{
		parent::__construct();
		$this->redirector = $redirector;
		$this->renderer = $renderer;
	}

	/**
	 * @param array $values
	 */
	public function loadState(array $values)
	{
		parent::loadState($values);

		$this->coffeeMachine = new CoffeeMachine(
			$this->state->money,
			$this->state->get('bought', null)
		);
		unset($this->state->bought);

		if (isset($this->state->do)) {
			$action = $this->state->do;
			unset($this->state->do);

			if ($action === 'insertMoney') {
				$amount = self::DEFAULT_COIN;
				if (isset($this->state->amount)) {
					$amount = (int) $this->state->amount;
					unset($this->state->amount);
				}
				$this->coffeeMachine->insertMoney($amount);
			}
			if ($action === 'buyCoffee') {
				$this->state->bought = $this->coffeeMachine->buyCoffee();
			}
			$this->state->money = $this->coffeeMachine->getMoney();

			$this->redirector->redirect($this->createLink());
		}
	}

	/**
	 * @return string
	 */
	public function readDisplay()
	{
		return $this->coffeeMachine->readDisplay();
	}

	/**
	 * @param int $amount
	 * @return string
	 */
	public function formatInsertMoneyLink($amount)
	{
		return $this->createLink(array(
			'do' => 'insertMoney',
			'amount' => $amount
		));
	}

	/**
	 * @return string
	 */
	public function formatBuyCoffeeLink()
	{
		return $this->createLink(array(
			'do' => 'buyCoffee',
		));
	}

	public function render()
	{
		$this->renderer->renderCoffeeMachine($this);
	}

}
