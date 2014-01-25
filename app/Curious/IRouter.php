<?php

namespace Curious;

use Nette\Http\IRequest;

/**
 * @author Vojtěch Kohout
 */
interface IRouter
{

	/**
	 * @param IRequest $request
	 * @return AppRequest
	 */
	public function match(IRequest $request);

	/**
	 * @param Fragment $fragment
	 * @param State $state
	 * @return string
	 */
	public function constructUrl(Fragment $fragment, State $state);

}