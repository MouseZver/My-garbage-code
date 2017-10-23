<?php

namespace Aero\Database;

use Aero;

class Migrate
{
	private static $instance;
	protected $migrate;
	protected $driver;
	
	public function __construct ( string $name )
	{
		$this -> migrate = ( $Lerma = new $name() ) -> migrate;
		
		$driverPath = 'driver' . DIRECTORY_SEPARATOR . $Lerma -> driver . '.php';
		
		if ( !file_exists ( __DIR__ . DIRECTORY_SEPARATOR . $driverPath ) )
		{
			throw new \Exception( 'Драйвер Lerma не найден. ' . $driverPath );
		}
		
		$this -> driver = require $driverPath;
	}
	protected static function instance()
	{
		if ( self::$instance === NULL )
		{
			self::$instance = new static ( Aero\Configures\Lerma::class );
		}
		
		return self::$instance;
	}
	protected static function query( $sql )
	{
		return ( $static = self::instance() ) -> driver -> query( is_array ( $sql ) ? sprintf ( ...$sql ) : $sql );
	}
	protected static function prepare( $sql, array $execute = [] )
	{
		if ( empty ( $execute ) )
		{
			throw new \Exception( 'Значения для подготовления пусты' );
		}
		
		( $static = self::instance() ) -> driver -> prepare( is_array ( $sql ) ? sprintf ( ...$sql ) : $sql );
		
		if ( $static -> isMulti( $execute ) )
		{
			$static -> multiExecute( $execute );
		}
		else
		{
			$static -> driver -> execute( $execute );
		}
		
		return $static -> driver;
	}
	protected function isMulti( array $array )
	{
		if ( is_array ( $array[0] ) )
		{
			foreach ( $array AS $items )
			{
				if ( !is_array ( $items ) )
				{
					throw new \Exception( 'Ошибка в мульти добавлении, запрос не выполнен. Ожидался полный многомерный массив.' );
				}
			}
			
			return TRUE;
		}
		else
		{
			foreach ( $array AS $items )
			{
				if ( is_array ( $items ) )
				{
					throw new \Exception( 'Ошибка в добавлении, запрос не выполнен. Ожидался не многомерный массив.' );
				}
			}
			
			return FALSE;
		}
	}
	protected function multiExecute( array $executes )
	{
		foreach ( $executes AS $execute )
		{
			self::$instance -> driver -> execute( $execute );
		}
	}
}