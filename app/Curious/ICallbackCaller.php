<?php

namespace Curious;

/**
 * @author Vojtěch Kohout
 */
interface ICallbackCaller
{

	/**
	 * @param string $functionName
	 * @return mixed
	 */
	public function callUsingAutowiring($functionName);
	
}
