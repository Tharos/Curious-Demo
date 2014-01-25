<?php
use Curious\Exception\InvalidStateException;
use Nette\DI\Container;


/**
 * @author Vojtěch Kohout
 */
class Directories
{

	/** @var array */
	private $parameters;


	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->parameters = $container->parameters;
	}

	/**
	 * @return string
	 */
	public function getTempDirectory()
	{
		return $this->readParameterValue('tempDir');
	}

	/**
	 * @return string
	 */
	public function getCacheDirectory()
	{
		return $this->readParameterValue('cacheDir');
	}

	/**
	 * @return string
	 */
	public function getTemplatesDirectory()
	{
		return $this->readParameterValue('templatesDir');
	}

	/**
	 * @return string
	 */
	public function getApplicationDirectory()
	{
		return $this->readParameterValue('appDir');
	}

	/**
	 * @return array
	 */
	public function getFragmentsDirectories()
	{
		return $this->readParameterValue('fragmentsDirectories');
	}

	////////////////////
	////////////////////

	/**
	 * @param string $parameter
	 * @return mixed
	 * @throws InvalidStateException
	 */
	private function readParameterValue($parameter)
	{
		if (!array_key_exists($parameter, $this->parameters)) {
			throw new InvalidStateException("Missing parameter '$parameter' in configuration.");
		}
		return $this->parameters[$parameter];
	}
	
}
