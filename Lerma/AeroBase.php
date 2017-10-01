<?php

namespace Aero;

class AeroBase
{
	protected static $NAMESPACESMAP = [];
	
	public static function autoload( string $C )
	{
		if ( isset ( self::$NAMESPACESMAP[$C] ) )	# Преобразование загруженной директории из имени пространства
		{
			$C = self::$NAMESPACESMAP[$C];
		}
		elseif ( file_exists ( __DIR__ . substr ( $C, 4 ) . '.php' ) )
		{
			$C = __DIR__ . substr ( $C, 4 );
		}
		else throw new \Exception( sprintf ( '~~ Invalid namespace %s', $C ) );
		
		include strtr ( $C, '\\', DIRECTORY_SEPARATOR ) . '.php';
	}
	public static function Separator( string $string )
	{
		return strtr ( $_SERVER['DOCUMENT_ROOT'] . $string, '/', DIRECTORY_SEPARATOR );
	}
}