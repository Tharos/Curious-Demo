<?php

namespace Curious;

use Closure;
use Curious\Exception\CircularDependencyException;
use Curious\Exception\InvalidArgumentException;
use Curious\Exception\InvalidStateException;

/**
 * @author Vojtěch Kohout
 */
class Fragment
{

	const CURRENT_FRAGMENT = 'this';

	/** @var string */
	private $name;

	/** @var string|array|Closure */
	private $callback;

	/** @var self[] */
	private $following = array();

	/** @var ICallbackCaller */
	private $callbackCaller;

	/** @var bool */
	private $isRunning;

	/** @var bool */
	private $isLocked = false;

	/** @var array */
	private $usedStates;


	/**
	 * @param string $name
	 * @param string|array|Closure $callback
	 * @param ICallbackCaller $callbackCaller
	 * @param array $usedStates
	 * @throws InvalidArgumentException
	 */
	public function __construct($name, $callback, ICallbackCaller $callbackCaller, array $usedStates = array())
	{
		if ($name === self::CURRENT_FRAGMENT) {
			throw new InvalidArgumentException("Fragment cannot have name '" . self::CURRENT_FRAGMENT . "'. That keyword has special meaning.");
		}
		$this->name = $name;
		$this->callback = $callback;
		$this->callbackCaller = $callbackCaller;
		$this->usedStates = $usedStates;
	}

	/**
	 * @param self $fragment
	 * @throws InvalidStateException
	 */
	public function follow(self $fragment)
	{
		if ($this->isLocked) {
			throw new InvalidStateException('Cannot add new following to locked fragment.');
		}
		$this->following[] = $fragment;
	}

	public function lock()
	{
		$this->isLocked = true;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function getAllUsedStates()
	{
		$usedStates = $this->usedStates;
		foreach ($this->following as $fragment) {
			$usedStates = array_merge($fragment->getAllUsedStates(), $usedStates);
		}
		return $usedStates;
	}

	/**
	 * @throws CircularDependencyException
	 * @return mixed
	 */
	public function run()
	{
		if ($this->isRunning) {
			throw new CircularDependencyException("Fragment $this->name is just running.");
		}
		$this->isRunning = true;
		foreach ($this->following as $fragment) {
			$fragment->run();
		}
		$result = $this->callbackCaller->callUsingAutowiring($this->callback);
		$this->isRunning = false;

		return $result;
	}
	
}
