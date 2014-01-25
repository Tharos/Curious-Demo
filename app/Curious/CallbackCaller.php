<?php

namespace Curious;

use Closure;
use Curious\Exception\InvalidArgumentException;
use Nette\DI\Container;

/**
 * @author Vojtěch Kohout
 */
class CallbackCaller implements ICallbackCaller
{

	/** @var Container */
	private $container;


	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * @param string|array|Closure $callback
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function callUsingAutowiring($callback)
	{
		if (!is_callable($callback)) {
			throw new InvalidArgumentException('Callback must be callable.');
		}
		return $this->container->callMethod($callback);
	}
	
}
