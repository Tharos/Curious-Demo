<?php

namespace Curious;

use Curious\Exception\InvalidStateException;

/**
 * @author Vojtěch Kohout
 */
abstract class Component
{

	/** @var State */
	protected $state;

	/** @var ComponentLinker */
	protected $componentLinker;

	/** @var array */
	protected $defaults = array();


	public function __construct()
	{
		$this->state = new State($this->defaults);
	}

	/**
	 * @param array $values
	 */
	public function loadState(array $values)
	{
		$this->state->merge($values);
	}

	/**
	 * @param ComponentLinker $componentLinker
	 */
	public function setComponentLinker(ComponentLinker $componentLinker)
	{
		$this->componentLinker = $componentLinker;
	}

	/**
	 * @return array
	 */
	public function getDefaults()
	{
		return $this->defaults;
	}

	/**
	 * @param array $stateChanges
	 * @throws InvalidStateException
	 * @return string
	 */
	protected function createLink(array $stateChanges = array())
	{
		if ($this->componentLinker === null) {
			throw new InvalidStateException('ComponentLinker is missing.');
		}
		$values = $this->state->export($stateChanges);
		foreach ($values as $field => $value) {
			if (array_key_exists($field, $this->defaults) and $value == $this->defaults[$field]) { // ==
				unset($values[$field]);
			}
		}
		return $this->componentLinker->createLink($values);
	}
	
}