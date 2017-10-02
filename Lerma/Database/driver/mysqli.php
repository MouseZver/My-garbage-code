<?php

use Lerma\Interfaces\LermaDrivers;

return new class ( $Lerma -> {$Lerma -> driver} ) implements LermaDrivers
{
	private $connect;
	private $statement;
	private $result;
	
	public function __construct ( array $params = [] )
	{
		if ( empty ( $params ) )
		{
			throw new Exception( 'Params expects most parameter values, returned empty' );
		}
		
		$params = array_values ( $params );
		
		$this -> connect = new mysqli( ...$params );
		$this -> connect -> set_charset( 'utf8' );
		
		if ( $this -> connect -> connect_error ) 
		{
			throw new Exception( 'Error connect (' . $this -> connect -> connect_errno . ') ' . $this -> connect -> connect_error );
		}
	}
	protected function error( $obj )
	{
		if ( $obj -> errno ) 
		{
			throw new Exception( $obj -> error );
		}
	}
	protected function result()
	{
		if ( is_a ( $this -> statement, mysqli_stmt::class ) )
		{
			return $this -> result;
		}
		
		return $this -> statement;
	}
	public function query( string $sql ): LermaDrivers
	{
		$this -> statement = $this -> connect -> query( $sql );
		$this -> error( $this -> connect );
		
		return $this;
	}
	public function prepare( string $sql ): LermaDrivers
	{
		$this -> statement = $this -> connect -> prepare( $sql );
		$this -> error( $this -> connect );
		
		return $this;
	}
	public function execute( array $arguments )
	{
		if ( !is_a ( $this -> statement, mysqli_stmt::class ) )
		{
			throw new Exception( 'Not execute' );
		}
		
		$types = array_map ( function ( $val )
		{
			if ( !in_array ( $type = gettype ( $val ), [ 'integer', 'double', 'string' ] ) )
			{
				throw new Exception( 'Invalid type ' . $type );
			}
			
			return $type{0};
		}, 
		$arguments );
		
		$arguments = array_values ( $arguments );
		
		extract ( $arguments, EXTR_PREFIX_ALL, 'bind' );
		
		$a = [];
		
		foreach ( $arguments AS $k => $arg ) 
		{
			$a[] = &${ 'bind_' . $k };
		}
		
		$arguments = array_merge ( [ implode ( '', $types ) ], $a );
		
		$this -> statement -> bind_param( ...$arguments );
		
		$bool = $this -> statement -> execute();
		$this -> result = $this -> statement -> get_result();
		
		$this -> error( $this -> statement );
		
		return ( $this -> result === FALSE ? $bool : $this );
	}
	public function fetch( int $fetch_style = 3 )
	{
		switch ( $fetch_style )
		{
			case 1:
				return $this -> result() -> fetch_array( MYSQLI_NUM );
			break;
			case 2:
				return $this -> result() -> fetch_array( MYSQLI_ASSOC );
			break;
			case 3:
				return $this -> result() -> fetch_array( MYSQLI_BOTH );
			break;
			case 4:
				return $this -> result() -> fetch_object();
			break;
			default:
				throw new Exception( 'Invalid fetch_style ' . $fetch_style . ' is not switch' );
		}
	}
	public function fetchAll( int $fetch_style = 3 ): array
	{
		switch ( $fetch_style )
		{
			case 1:
				return $this -> result() -> fetch_all( MYSQLI_NUM );
			break;
			case 2:
				return $this -> result() -> fetch_all( MYSQLI_ASSOC );
			break;
			case 3:
				return $this -> result() -> fetch_all( MYSQLI_BOTH );
			break;
			case 4:
				$all = [];
				
				while ( $res = $this -> fetch( $fetch_style ) ) 
				{ 
					$all[] = $res;
				}
				
				return $all;
			break;
			default:
				throw new Exception( 'Invalid fetch_style ' . $fetch_style . ' is not switch' );
		}
	}
	public function fetchColumn()
	{
		return $this -> result() -> fetch_array( MYSQLI_NUM )[0];
	}
	public function rowCount(): int
	{
		return $this -> result() -> num_rows;
	}
	public function InsertId(): int
	{
		return $this -> result() -> insert_id;
	}
	public function __call( $method, $arguments )
	{
		/* if ( method_exists ( $this, $callback = '_' . $method ) )
			return call_user_func_array ( [ $this, $callback ], $arguments ); */
		
		
		return call_user_func_array ( [ $this -> statement, $method ], $arguments );
	}
};