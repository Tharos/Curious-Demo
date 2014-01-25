<?php

namespace Curious;

use Curious\Exception\InvalidArgumentException;
use Curious\Exception\InvalidStateException;

/**
 * @author Vojtěch Kohout
 */
class Components
{

	/** @var array */
	private $components;

	/** @var State */
	private $state;

	/** @var Linker */
	private $linker;


	/**
	 * @param State $state
	 * @param Linker $linker
	 */
	public function __construct(State $state, Linker $linker)
	{
		$this->state = $state;
		$this->linker = $linker;
	}

	/**
	 * @param string $name
	 * @param object $component
	 * @param string|null $stateField
	 * @throws InvalidStateException
	 * @throws InvalidArgumentException
	 */
	public function register($name, $component, $stateField = null)
	{
		if (!is_object($component)) {
			throw new InvalidArgumentException('Component must be an object.');
		}
		if (isset($this->components[$name])) {
			throw new InvalidStateException("Component with name '$name' is already registered.");
		}
		$this->components[$name] = $component;
		if ($component instanceof Component) {
			$stateField = $stateField !== null ? (string) $stateField : $name;
			$component->setComponentLinker(
				new ComponentLinker($stateField, $this->linker)
			);
			$component->loadState(isset($this->state->$stateField) ? $this->state->$stateField : array());
		}
	}

	/**
	 * @param string $name
	 * @return object
	 * @throws InvalidArgumentException
	 */
	public function get($name)
	{
		if (!isset($this->components[$name])) {
			throw new InvalidArgumentException("Component with name '$name' was not registred.");
		}
		return $this->components[$name];
	}
	
}
