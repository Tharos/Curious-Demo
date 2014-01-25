<?php

use Curious\Exception\BadRequestException;
use Curious\Fragments;
use Nette\Diagnostics\Debugger;
use Nette\Templating\FileTemplate;

/**
 * @author Vojtěch Kohout
 */
class ErrorHandler
{

	/** @var string */
	private $templatesDirectory;

	/** @var FileTemplate */
	private $template;

	/** @var Fragments */
	private $fragments;


	/**
	 * @param string $templatesDirectory
	 * @param FileTemplate $template
	 * @param Fragments $fragments
	 */
	public function __construct($templatesDirectory, FileTemplate $template, Fragments $fragments)
	{
		$this->templatesDirectory = $templatesDirectory;
		$this->template = $template;
		$this->fragments = $fragments;
	}

	/**
	 * @param Exception $e
	 */
	public function handleException(Exception $e)
	{
		if ($e instanceof BadRequestException) {
			$this->fragments->get('404')->run();
		} else {
			Debugger::log($e);
			http_response_code(500);
			$this->template->setFile($this->templatesDirectory . '/500.latte');
			$this->template->render();
			exit;
		}
	}

}
