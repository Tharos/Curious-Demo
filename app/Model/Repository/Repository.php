<?php

namespace Model\Repository;

use Curious\Exception\BadRequestException;
use LeanMapper\Entity;
use Model\Query\IQuery;

/**
 * @author Vojtěch Kohout
 */
abstract class Repository extends \LeanMapper\Repository
{

	/**
	 * @param mixed $id
	 * @return Entity
	 * @throws BadRequestException
	 */
	public function find($id)
	{
		$table = $this->getTable();
		$primaryKey = $this->mapper->getPrimaryKey($table);
		$row = $this->createFluent()->where('%n.%n = ?', $table, $primaryKey, $id)->fetch();
		if ($row === false) {
			$entityClass = $this->mapper->getEntityClass($table);
			throw new BadRequestException("Entity $entityClass with ID $id was not found.");
		}
		return $this->createEntity($row);
	}

	/**
	 * @return Entity[]
	 */
	public function findAll()
	{
		return $this->createEntities(
			$this->createFluent()->fetchAll()
		);
	}

	/**
	 * @param IQuery $query
	 * @return Entity[]
	 */
	public function findBy(IQuery $query)
	{
		$entityClass = $this->mapper->getEntityClass($this->getTable());
		$fluent = $this->createFluent();
		$query->apply($fluent, $entityClass::getReflection($this->mapper));
		return $this->createEntities($fluent->fetchAll());
	}

	/**
	 * @param IQuery $query
	 * @return Entity
	 * @throws BadRequestException
	 */
	public function findOneBy(IQuery $query)
	{
		$entityClass = $this->mapper->getEntityClass($this->getTable());
		$fluent = $this->createFluent();
		$query->apply($fluent, $entityClass::getReflection($this->mapper));
		foreach (array('limit', 'offset') as $clause) {
			$fluent->removeClause($clause);
		}
		$row = $fluent->fetch();
		if ($row === false) {
			throw new BadRequestException("No $entityClass did match given criteria.");
		}
		return $this->createEntity($row);
	}

	/**
	 * @param IQuery $query
	 * @return int
	 */
	public function findCountBy(IQuery $query)
	{
		$entityClass = $this->mapper->getEntityClass($this->getTable());
		$fluent = $this->createFluent();
		$query->apply($fluent, $entityClass::getReflection($this->mapper));
		foreach (array('limit', 'offset', 'select') as $clause) {
			$fluent->removeClause($clause);
		}
		return $fluent->select('COUNT(*)')->fetchSingle();
	}
	
}