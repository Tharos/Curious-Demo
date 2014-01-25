<?php

use Curious\Linker;
use Curious\Fragment;
use Nette\Caching\IStorage;
use Nette\Http\Url;
use Nette\Templating\FileTemplate;
use Nette\Latte;

/**
 * @author Vojtěch Kohout
 */
class TemplateFactory
{

	/** @var string */
	private $templatesDirectory;

	/** @var IStorage */
	private $cacheStorage;

	/** @var Linker */
	private $linker;

	/** @var Url */
	private $refUrl;


	/**
	 * @param string $templatesDirectory
	 * @param IStorage $cacheStorage
	 * @param Url $refUrl
	 * @param Linker $linker
	 */
	public function __construct($templatesDirectory, IStorage $cacheStorage, Url $refUrl, Linker $linker)
	{
		$this->templatesDirectory = $templatesDirectory;
		$this->cacheStorage = $cacheStorage;
		$this->linker = $linker;
		$this->refUrl = $refUrl;
	}

	/**
	 * @param string|null $name
	 * @return FileTemplate
	 */
	public function createTemplate($name = null)
	{
		$template = new FileTemplate;

		$template->registerFilter(new Latte\Engine);
		$template->registerHelperLoader('Nette\Templating\Helpers::loader');
		$template->setCacheStorage($this->cacheStorage);

		$template->basePath = $this->refUrl->getBasePath();
		$template->linker = $this->linker;

		if ($name !== null) {
			$template->setFile($this->templatesDirectory . "/$name.latte");
		}
		return $template;
	}

}
