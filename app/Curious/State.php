<?php

namespace Curious;

use Curious\Exception\InvalidArgumentException;

/**
 * @author Vojtěch Kohout
 */
class State
{

	/** @var array */
	private $values;


	/**
	 * @param array $values
	 */
	public function __construct(array $values = array())
	{
		$this->load($values);
	}

	/**
	 * @param string $field
	 * @return mixed
	 */
	public function __get($field)
	{
		return $this->get($field);
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 */
	public function __set($field, $value)
	{
		$this->set($field, $value);
	}

	/**
	 * @param string $field
	 * @return bool
	 */
	public function __isset($field)
	{
		return $this->has($field); // field cannot contain null
	}

	/**
	 * @param string $field
	 */
	public function __unset($field)
	{
		unset($this->values[$field]);
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 */
	public function set($field, $value)
	{
		if ($value === null) {
			unset($this->values[$field]);
		} else {
			$this->checkType($value);
			$this->values[$field] = $value;
		}
	}

	/**
	 * @param string $field
	 * @param mixed|null $defaultValue
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function get($field, $defaultValue = null)
	{
		if (!$this->has($field)) {
			if (func_num_args() === 2) {
				return $defaultValue;
			}
			throw new InvalidArgumentException("State doesn't contain field $field.");
		}
		return $this->values[$field];
	}

	/**
	 * @param string $pattern
	 * @return array
	 */
	public function match($pattern)
	{
		$values = array();
		$pattern = str_replace('#', '\#', $pattern);
		foreach ($this->values as $field => $value) {
			if (preg_match("#$pattern#", $field)) {
				$values[$field] = $value;
			}
		}
		return $values;
	}

	/**
	 * @param string $field
	 * @return bool
	 */
	public function has($field)
	{
		return array_key_exists($field, $this->values);
	}

	/**
	 * @param array $values
	 */
	public function merge(array $values)
	{
		foreach ($values as $field => $value) {
			$this->set($field, $value);
		}
	}

	/**
	 * @param array $values
	 */
	public function load(array $values)
	{
		$this->values = array();
		$this->merge($values);
	}

	/**
	 * @param array $state
	 * @return array
	 */
	public function export(array $state = array())
	{
		$merged = array_merge($this->values, $state);
		foreach ($merged as $field => $value) {
			if ($value === null) {
				unset($merged[$field]);
			}
		}
		return $merged;
	}

	////////////////////
	////////////////////

	/**
	 * @param mixed $value
	 * @throws InvalidArgumentException
	 */
	private function checkType($value)
	{
		if (!is_scalar($value) and !is_array($value)) {
			throw new InvalidArgumentException('Value in State must be scalar or array.');
		}
	}

}
