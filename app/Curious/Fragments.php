<?php

namespace Curious;

use Curious\Exception\InvalidArgumentException;

/**
 * @author Vojtěch Kohout
 */
class Fragments
{

	/** @var Fragment[] */
	private $index;


	/**
	 * @param Fragment[] $index
	 */
	public function __construct($index)
	{
		$this->index = $index;
	}

	/**
	 * @param string $name
	 * @return Fragment
	 * @throws InvalidArgumentException
	 */
	public function get($name)
	{
		if (!isset($this->index[$name])) {
			throw new InvalidArgumentException("Fragment $name cannot be found.");
		}
		return $this->index[$name];
	}
	
}
