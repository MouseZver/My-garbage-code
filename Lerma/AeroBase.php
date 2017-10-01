<?php

class Aero
{
	public static function Separator( string $string )
	{
		return strtr ( $_SERVER['DOCUMENT_ROOT'] . $string, '/', DIRECTORY_SEPARATOR );
	}
}