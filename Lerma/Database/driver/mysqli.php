<?php

use Aero\Interfaces\LermaDrivers;

return new class ( $Lerma -> {$Lerma -> driver} ) implements LermaDrivers
{
	private $connect;
	private $statement;
	private $result;
	private $bind_result = [];
	
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
			throw new Exception( sprintf ( 'Error connect (%s) %s', $this -> connect -> connect_errno, $this -> connect -> connect_error ) );
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
			return $this -> result ?? $this -> result = $this -> statement -> get_result();
		}
		
		return $this -> statement;
	}
	protected function bind()
	{
		if ( is_a ( $this -> statement, mysqli_stmt::class ) )
		{
			if ( empty ( $this -> bind_result ) )
			{
				for ( $i = 0; $i < $this -> statement -> field_count; $i++, $this -> bind_result[] = &${ 'result_' . $i } );
				
				$this -> statement -> bind_result( ...$this -> bind_result );
			}
			
			return $this -> statement;
		}
		
		throw new Exception( 'Not bind result to query empty placeholders' );
	}
	protected function dead(): LermaDrivers
	{
		$this -> result = null;
		$this -> bind_result = [];
		
		if ( $this -> statement !== null )
		{
			$this -> statement -> close();
		}
		
		return $this;
	}
	public function query( string $sql ): LermaDrivers
	{
		$this -> statement = $this -> dead() -> connect -> query( $sql );
		$this -> error( $this -> connect );
		
		return $this;
	}
	public function prepare( string $sql ): LermaDrivers
	{
		$this -> statement = $this -> dead() -> connect -> prepare( $sql );
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
		#$this -> result = $this -> statement -> get_result();
		
		$this -> error( $this -> statement );
		
		return ( $this -> statement -> field_count === 0 ? $bool : $this );
	}
	public function fetch( int $fetch_style = 686, $callable = null )
	{
		switch ( $fetch_style )
		{
			case 686:
				return $this -> result() -> fetch_array( MYSQLI_NUM );
			break;
			case 667:
				return $this -> result() -> fetch_array( MYSQLI_ASSOC );
			break;
			case 625:
				return $this -> result() -> fetch_object();
			break;
			case 663:
			case 927:
				if ( !$this -> bind() -> fetch() )
				{
					return $this -> bind_result = null;
				}
				
				switch ( $fetch_style )
				{
					case 927:
						if ( $this -> statement -> field_count !== 1 )
						{
							throw new Exception( 'the number of fields must be one' );
						}
						
						return $this -> bind_result[0] ?? null;
					break;
				}
				
				return $this -> bind_result;
			break;
			case 265:
				if ( $this -> statement -> field_count !== 1 )
				{
					throw new Exception( 'the number of fields must be one' );
				}
				
				return $this -> result() -> fetch_array( MYSQLI_NUM )[0];
			break;
			case 0000:
				return $callable( ...$this -> result() -> fetch_array( MYSQLI_NUM )[0] );
			break;
			default:
				throw new Exception( sprintf ( 'Invalid fetch_style %s is not switch', $fetch_style ) );
		}
	}
	public function fetchAll( int $fetch_style = 686 ): array
	{
		switch ( $fetch_style )
		{
			case 686:
				return $this -> result() -> fetch_all( MYSQLI_NUM );
			break;
			case 667:
				return $this -> result() -> fetch_all( MYSQLI_ASSOC );
			break;
			case 625:
			case 663:
			case 265:
				$all = [];
				
				while ( $res = $this -> fetch( $fetch_style ) ) { $all[] = $res; }
				
				return $all;
			break;
			default:
				throw new Exception( sprintf ( 'Invalid fetch_style %s is not switch', $fetch_style ) );
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
	public function InsertID(): int
	{
		return $this -> statement -> insert_id ?? 0;
	}
	/* public function __call( $method, $arguments )
	{
		return $this -> statement -> $method( ...$arguments );
	} */
};