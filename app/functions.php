<?php

if (!function_exists('http_response_code')) {
	function http_response_code($newcode = NULL)
	{
		static $code = 200;
		if ($newcode !== NULL) {
			header('X-PHP-Response-Code: ' . $newcode, true, $newcode);
			if (!headers_sent())
				$code = $newcode;
		}
		return $code;
	}
}
