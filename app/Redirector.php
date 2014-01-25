<?php


/**
 * @author Vojtěch Kohout
 */
class Redirector
{

	/**
	 * @param string $url
	 */
	public function redirect($url)
	{
		header('Location: ' . $url);
		die();
	}
	
}
