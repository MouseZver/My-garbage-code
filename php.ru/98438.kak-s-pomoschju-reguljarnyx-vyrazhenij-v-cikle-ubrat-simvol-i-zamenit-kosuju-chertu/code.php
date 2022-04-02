<?php

$output = new SplFileObject( 'output', 'w' );

foreach ( new SplFileObject( 'input', 'r' ) AS $id => $line )
{
	$line = str_replace ( '/', ',', trim ( $line ) );
	
	$array = array_filter ( explode ( ',', $line ), 'is_numeric' );
	
	$output -> fwrite ( implode ( ',', $array ) . PHP_EOL );
}
