<?php

use Curious\AppRequest;
use Curious\Exception\InvalidArgumentException;
use Curious\Fragment;
use Curious\Fragments;
use Curious\State;
use Nette\Http\IRequest;
use Nette\Http\Url;

/**
 * @author Vojtěch Kohout
 */
class Router implements \Curious\IRouter
{

	const DEFAULT_FRAGMENT_NAME = 'homepage';

	/** @var Url */
	private $refUrl;

	/** @var Fragments */
	private $fragments;


	/**
	 * @param Url $refUrl
	 * @param Fragments $fragments
	 */
	public function __construct(Url $refUrl, Fragments $fragments)
	{
		$this->refUrl = $refUrl;
		$this->fragments = $fragments;
	}

	/*
	 * @inheritdoc
	 */
	public function match(IRequest $request)
	{
		$pathInfo = $request->getUrl()->getPathInfo();
		try {
			$fragment = $this->fragments->get($pathInfo === '' ? self::DEFAULT_FRAGMENT_NAME : $pathInfo);
		} catch (InvalidArgumentException $e) {
			$fragment = $this->fragments->get('404');
		}
		return new AppRequest($fragment, new State($request->getQuery()));
	}

	/*
	 * @inheritdoc
	 */
	public function constructUrl(Fragment $fragment, State $state)
	{
		$url = $fragment->getName();
		$state = $state->export();

		if ($url === self::DEFAULT_FRAGMENT_NAME) {
			$url = '';
		}
		$queryString = http_build_query($state);
		if ($queryString !== '') {
			$url .= '?' . $queryString;
		}
		return $this->refUrl->getBasePath() . $url;
	}

}
