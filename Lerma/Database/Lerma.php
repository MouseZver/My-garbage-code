<?php

namespace Lerma\Database;

class Lerma extends Migrate
{
	public const FETCH_NUM = 1;
	public const FETCH_ASSOC = 2;
	public const FETCH_BOTH = 3;
	public const FETCH_OBJ = 4;
	
	public static function select( array $execute, callable $callable )
	{
		self::load( __METHOD__, ( $execute ?: NULL ), $callable );
	}
	public static function insert( array $execute, callable $callable )
	{
		
	}
	public static function create( array $execute, callable $callable )
	{
		
	}
	public static function delete( array $execute, callable $callable )
	{
		
	}
	public static function __callStatic( $method, $args )
	{
		if ( in_array ( $method, [ 'query', 'prepare' ] ) )
		{
			return self::$method( ...$args );
		}
		elseif ( isset ( self::$start[$method] ) )
		{
			return self::load( self::$start[$method], ...$args );
		}
		
		return self::instance() -> $method( ...$args );
	}
}