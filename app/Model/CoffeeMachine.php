<?php

namespace Model;

/**
 * @author Vojtěch Kohout
 */
class CoffeeMachine
{

	const COFFEE_PRICE = 20;

	/** @var int */
	private $money;

	/** @var bool|null */
	private $bought;


	/**
	 * @param int $money
	 * @param bool|null $bought
	 */
	public function __construct($money = 0, $bought = null)
	{
		$this->money = (int) $money;
		$this->bought = $bought === null ? null : (bool) $bought;
	}

	/**
	 * @return string
	 */
	public function readDisplay()
	{
		if ($this->bought === true) {
			return 'Děkujeme za zakoupení kávy!';
		} elseif ($this->bought === false) {
			return 'Litujeme, ale kafé opravdu stojí ' . self::COFFEE_PRICE . ' Kč';
		} elseif ($this->money === 0) {
			return 'Vložte prosím ' . self::COFFEE_PRICE . ' Kč';
		} else {
			return 'Váš kredit je ' . $this->money . ' Kč';
		}
	}

	/**
	 * @param int $amount
	 */
	public function insertMoney($amount)
	{
		$this->money = $this->money + (int) $amount;
		$this->bought = null;
	}

	/**
	 * @return bool
	 */
	public function buyCoffee()
	{
		if ($this->money >= self::COFFEE_PRICE) {
			$this->money = 0;
			$this->bought = true;
			return true;
		} else {
			$this->bought = false;
			return false;
		}
	}

	/**
	 * @return int
	 */
	public function getMoney()
	{
		return $this->money;
	}
	
}
