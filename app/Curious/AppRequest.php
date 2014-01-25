<?php

namespace Curious;

/**
 * @author Vojtěch Kohout
 */
class AppRequest
{

	/** @var Fragment */
	private $fragment;

	/** @var State */
	private $state;


	/**
	 * @param Fragment $fragment
	 * @param State $state
	 */
	public function __construct(Fragment $fragment, State $state = null)
	{
		$this->fragment = $fragment;
		$this->state = $state === null ? new State : $state;
	}

	/**
	 * @return Fragment
	 */
	public function getFragment()
	{
		return $this->fragment;
	}

	/**
	 * @return State
	 */
	public function getState()
	{
		return $this->state;
	}
	
}
