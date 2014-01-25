<?php

namespace Model\Entity;

use LeanMapper\Relationship\HasOne;

/**
 * @author Vojtěch Kohout
 */
abstract class Entity extends \LeanMapper\Entity
{

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value)
	{
		$property = $this->getCurrentReflection()->getEntityProperty($name);
		$relationship = $property->getRelationship();
		if (($relationship instanceof HasOne) and !($value instanceof \LeanMapper\Entity)) {
			if (is_string($value) and ctype_digit($value)) {
				settype($value, 'integer');
			}
			$this->row->{$property->getColumn()} = $value;
			$this->row->cleanReferencedRowsCache($relationship->getTargetTable(), $relationship->getColumnReferencingTargetTable());
		} else {
			parent::__set($name, $value);
		}
	}

}
