<?php

use Curious\Exception\InvalidArgumentException;
use Curious\Fragment;
use Curious\Linker;
use Nette\Templating\FileTemplate;

/**
 * @author Vojtěch Kohout
 */
class Navigation
{

	/** @var Fragment */
	private $fragment;

	/** @var TemplateFactory */
	private $templateFactory;

	/** @var array */
	private $fragments = array(
		Router::DEFAULT_FRAGMENT_NAME => 'Úvod',
		'curious' => 'O Curious',
		'coffee' => 'Dáte si kávu?',
		'coffees' => 'Jedna nestačí?',
		'users' => 'Uživatelé',
	);

	/** @var string */
	private $activeFragmentName;

	/** @var FileTemplate */
	private $template;


	/**
	 * @param Fragment $fragment
	 * @param TemplateFactory $templateFactory
	 */
	public function __construct(Fragment $fragment, TemplateFactory $templateFactory)
	{
		$this->fragment = $fragment;
		$this->templateFactory = $templateFactory;
	}

	/**
	 * @param string $fragmentName
	 * @return bool
	 */
	public function hasFragment($fragmentName)
	{
		return array_key_exists($fragmentName, $this->fragments);
	}

	/**
	 * @param string|null $fragmentName
	 * @throws InvalidArgumentException
	 */
	public function setActiveFragment($fragmentName)
	{
		if ($fragmentName !== null and !array_key_exists($fragmentName, $this->fragments)) {
			throw new InvalidArgumentException("Fragment $fragmentName was not found.");
		}
		$this->activeFragmentName = $fragmentName;
	}

	public function renderMenu()
	{
		$template = $this->getMenuTemplate();
		$template->fragments = $this->fragments;
		$template->activeFragmentName = $this->activeFragmentName;
		$template->render();
	}

	public function renderTitle()
	{
		if ($this->activeFragmentName !== null) {
			echo $this->fragments[$this->activeFragmentName];
		}
	}

	////////////////////
	////////////////////

	/**
	 * @return FileTemplate
	 */
	private function getMenuTemplate()
	{
		if ($this->template === null) {
			$this->template = $template = $this->templateFactory->createTemplate('components/menu');
		}
		return $this->template;
	}
	
}
