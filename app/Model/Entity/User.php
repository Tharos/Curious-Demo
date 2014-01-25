<?php

namespace Model\Entity;

use InvalidArgumentException;
use Nette\Utils\Validators;

/**
 * @author Vojtěch Kohout
 *
 * @property int $id
 * @property string $name
 * @property string $email m:passThru(checkEmail)
 * @property string|null $note
 */
class User extends Entity
{

	/**
	 * @param string $email
	 * @return string
	 * @throws InvalidArgumentException
	 */
	protected function checkEmail($email)
	{
		if (!Validators::isEmail($email)) {
			throw new InvalidArgumentException("Invalid e-mail address given: " . $email);
		}
		return $email;
	}
	
}
