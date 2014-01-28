<?php

namespace Curious;

use Curious\Exception\InvalidArgumentException;

/**
 * @author Vojtěch Kohout
 */
class Linker
{

	/** @var Fragments */
	private $fragments;

	/** @var Fragment */
	private $fragment;

	/** @var State */
	private $state;

	/** @var IRouter */
	private $router;


	/**
	 * @param Fragments $fragments
	 * @param Fragment $fragment
	 * @param State $state
	 * @param IRouter $router
	 */
	public function __construct(Fragments $fragments, Fragment $fragment, State $state, IRouter $router)
	{
		$this->fragments = $fragments;
		$this->fragment = $fragment;
		$this->state = $state;
		$this->router = $router;
	}

	/**
	 * @param string $fragmentName
	 * @param array|bool|null $parameters  Use false for state for throwing away all fields
	 * @throws InvalidArgumentException
	 * @return string
	 */
	public function createLink($fragmentName, $parameters = null)
	{
		if ($fragmentName === Fragment::CURRENT_FRAGMENT) {
			if ($parameters === null) {
				return $this->router->constructUrl($this->fragment, $this->state);
			}
			$fragment = $this->fragment;
		} else {
			$fragment = $this->fragments->get($fragmentName);
		}
		$targetState = new State;

		if ($parameters === false) {
			$targetParameters = array();
		} else {
			if ($parameters !== null and !is_array($parameters)) {
				throw new InvalidArgumentException('Parameters must be null, array or false.');
			}
			$targetParameters = $parameters ? : array();

			foreach ($fragment->getAllUsedStates() as $stateField) {
				$matches = array();
				if (preg_match('#^\{(.*)\}$#', $stateField, $matches)) {
					foreach ($this->state->match($matches[1]) as $matchedStateField => $value) {
						if (!array_key_exists($matchedStateField, $targetParameters)) {
							$targetParameters[$matchedStateField] = $value;
						}
					}
				} else {
					if (isset($this->state->$stateField) and !array_key_exists($stateField, $targetParameters)) {
						$targetParameters[$stateField] = $this->state->$stateField;
					}
				}
			}

		}
		$targetState->load($targetParameters);

		return $this->router->constructUrl($fragment, $targetState);
	}

}
