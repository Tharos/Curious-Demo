<?php

namespace Curious;

use Curious\Exception\InvalidStateException;
use Nette\Utils\Finder;

/**
 * @author Vojtěch Kohout
 */
class FragmentsFactory implements IFragmentsFactory
{

	/** @var array */
	private $fragmentsDirectories;

	/** @var Finder */
	private $finder;

	/** @var ICallbackCaller */
	private $callbackCaller;


	/**
	 * @param array $fragmentsDirectories
	 * @param Finder $finder
	 * @param ICallbackCaller $callbackCaller
	 */
	public function __construct(array $fragmentsDirectories, Finder $finder, ICallbackCaller $callbackCaller)
	{
		$this->fragmentsDirectories = $fragmentsDirectories;
		$this->finder = $finder;
		$this->callbackCaller = $callbackCaller;
	}

	/**
	 * @throws InvalidStateException
	 * @return Fragments
	 */
	public function createFragments()
	{
		$index = array();
		$followings = array();
		$requires = array();

		foreach ($this->finder->findFiles('*.php')->from(reset($this->fragmentsDirectories)) as $fileInfo) {
			$source = file_get_contents($fileInfo->getRealPath());
			$matches = array();
			preg_match_all('#(/\*\*.*?\*/)\s*function\s*([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\(#ims', $source, $matches, PREG_SET_ORDER);
			if (!empty($matches)) {
				foreach ($matches as $match) {
					$subMatches = array();
					preg_match('#@fragment\s+([a-zA-Z0-9_\x7f-\xff-]+)#', $match[1], $subMatches);
					if (empty($subMatches)) continue;

					$requires[] = $fileInfo->getRealPath();
					$fragmentName = $subMatches[1];
					if (isset($index[$fragmentName])) {
						throw new InvalidStateException("Multiple declarations of fragment $subMatches[1] found.");
					}

					$subMatches = array();
					$usedStates = array();
					preg_match('#@usingState\s+((?:[^\s,]+)(?:,\s*[^\s,]+)*)#', $match[1], $subMatches); // TODO: improve pattern
					if (!empty($subMatches)) {
						$usedStates = preg_split('#\s*,\s*#', $subMatches[1]);
					}
					$index[$fragmentName] = new Fragment($fragmentName, $match[2], $this->callbackCaller, $usedStates);

					$subMatches = array();
					preg_match('#@following\s+((?:[a-zA-Z0-9_\x7f-\xff]+(?:,\s*[a-zA-Z0-9_\x7f-\xff]+)*))#', $match[1], $subMatches);
					if (!empty($subMatches)) {
						$followings[$fragmentName] = preg_split('#\s*,\s*#', $subMatches[1]);
					}
				}
			}
		}
		foreach ($followings as $fragmentName => $followedFragmentsNames) {
			foreach ($followedFragmentsNames as $followedFragmentName) {
				$index[$fragmentName]->follow($index[$followedFragmentName]);
			}
		}
		$this->lockFragments($index);

		foreach ($requires as $require) {
			require_once $require;
		}
		return new Fragments($index);
	}

	////////////////////
	////////////////////

	/**
	 * @param Fragment[] $index
	 */
	private function lockFragments(array $index)
	{
		foreach ($index as $fragment) {
			$fragment->lock();
		}
	}

}
