<?php

namespace Aero\Database;

class Lerma extends Migrate
{
	
	public static function select( array $execute, callable $callable )
	{
		self::load
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
		return call_user_func_array ( [ self::instance(), $method ], $args );
	}
}