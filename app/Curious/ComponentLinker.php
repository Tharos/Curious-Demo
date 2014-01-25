<?php

namespace Curious;

/**
 * @author Vojtěch Kohout
 */
class ComponentLinker
{

	/** @var string */
	private $stateField;

	/** @var Linker */
	private $linker;


	/**
	 * @param string $stateField
	 * @param Linker $linker
	 */
	public function __construct($stateField, Linker $linker)
	{
		$this->stateField = $stateField;
		$this->linker = $linker;
	}

	/**
	 * @param array|null $parameters
	 * @return string
	 */
	public function createLink(array $parameters = null)
	{
		return $this->linker->createLink(Fragment::CURRENT_FRAGMENT, array(
			$this->stateField => $parameters
		));
	}

}
