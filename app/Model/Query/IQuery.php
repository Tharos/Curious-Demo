<?php

namespace Model\Query;

use LeanMapper\Fluent;
use LeanMapper\Reflection\EntityReflection;

/**
 * @author Vojtěch Kohout
 */
interface IQuery
{

	/**
	 * @param Fluent $fluent
	 * @param EntityReflection $entityReflection
	 */
	public function apply(Fluent $fluent, EntityReflection $entityReflection);
	
}
 