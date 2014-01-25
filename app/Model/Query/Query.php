<?php

namespace Model\Query;

use LeanMapper\Exception\InvalidMethodCallException;
use LeanMapper\Fluent;
use LeanMapper\Reflection\AnnotationsParser;
use LeanMapper\Reflection\EntityReflection;
use ReflectionClass;

/**
 * @author Vojtěch Kohout
 */
class Query implements IQuery
{

	const ORDER_ASC = 'asc';

	const ORDER_DESC = 'desc';

	/** @var int */
	protected $limit;

	/** @var int */
	protected $offset;

	/** @var array */
	protected $filter = array();

	/** @var array */
	protected $orders = array();

	/** @var array */
	private $magicMethods;


	public function __construct()
	{
		$reflection = new ReflectionClass($this);
		foreach (AnnotationsParser::parseAnnotationValues('method', $reflection->getDocComment()) as $value) {
			$matches = array();
			preg_match('#([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(?:\(|$)#', $value, $matches);
			if (isset($matches[1])) {
				$this->magicMethods[$matches[1]] = true;
			}
		}
	}

	/**
	 * @param int $limit
	 * @return static
	 */
	public function limit($limit)
	{
		$this->limit = $limit;
		return $this;
	}

	/**
	 * @param int $offset
	 * @return static
	 */
	public function offset($offset)
	{
		$this->offset = $offset;
		return $this;
	}

	/**
	 * @param string $name
	 * @param mixed $arg
	 * @throws InvalidMethodCallException
	 * @return static
	 */
	public function __call($name, $arg)
	{
		if (isset($this->magicMethods[$name])) {
			if (strpos($name, 'restrict') === 0) {
				$this->filter[lcfirst(substr($name, 8))] = $arg;
				return $this;
			}
			if (strpos($name, 'orderBy') === 0) {
				$this->orders[] = array(lcfirst(substr($name, 7)), self::ORDER_ASC);
				return $this;
			}
			if (strpos($name, 'orderDescBy') === 0) {
				$this->orders[] = array(lcfirst(substr($name, 11)), self::ORDER_DESC);
				return $this;
			}
		}
		throw new InvalidMethodCallException("Cannot call method $name on object " . get_called_class() . '.');
	}

	/**
	 * @param Fluent $fluent
	 * @param EntityReflection $entityReflection
	 */
	public function apply(Fluent $fluent, EntityReflection $entityReflection)
	{
		foreach ($this->filter as $propertyName => $value) {
			$property = $entityReflection->getEntityProperty($propertyName);
			$fluent->where('%n = ?', $property->getColumn(), $value);
		}
		foreach ($this->orders as $order) {
			$property = $entityReflection->getEntityProperty($order[0]);
			$fluent->orderBy($property->getColumn());
			if ($order[1] === self::ORDER_DESC) {
				$fluent->desc();
			}
		}
		$this->limit === null or $fluent->limit($this->limit);
		$this->offset === null or $fluent->offset($this->offset);
	}

}