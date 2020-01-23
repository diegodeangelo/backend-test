<?php

namespace App\Utils;

class Sanitize
{
	public static function string($string): string
	{
		return filter_var($string, FILTER_SANITIZE_STRING);
	}

	public static function integer($string): string
	{
		return filter_var($string, FILTER_SANITIZE_STRING);
	}
}