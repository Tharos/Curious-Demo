<?php

namespace Model\Query;

use LeanMapper\Fluent;
use LeanMapper\Reflection\EntityReflection;

/**
 * @author Vojtěch Kohout
 */
class UserQuery extends Query
{

	/** @var string */
	private $keyword;


	/**
	 * @param string $keyword
	 * @return self
	 */
	public function filterByKeyword($keyword)
	{
		$this->keyword = $keyword;
		return $this;
	}

	/**
	 * @param Fluent $fluent
	 * @param EntityReflection $entityReflection
	 */
	public function apply(Fluent $fluent, EntityReflection $entityReflection)
	{
		parent::apply($fluent, $entityReflection);
		if ($this->keyword !== null) {
			$or = array();
			foreach (array('email', 'name', 'note') as $field) {
				$property = $entityReflection->getEntityProperty($field);
				$or[] = array('%n LIKE %~like~', $property->getColumn(), $this->keyword);
			}
			$fluent->where('(%or)', $or);
		}
	}
	
}
