<?php

declare ( strict_types = 1 );

error_reporting ( E_ALL );




$O_o = ' - first line O_o';

$write = new SplFileObject( 'write.txt', 'w' );

foreach ( new SplFileObject( 'read.txt', 'r' ) AS $id => $line )
{
	if ( empty ( trim ( $line ) ) )
	{
		continue;
	}
	
	$isFirst = true;
	
	foreach ( explode ( ' ', trim ( $line ) ) AS $segment )
	{
		if ( $isFirst )
		{
			$isFirst = false;
			
			$segment .= $O_o;
		}
		
		$write -> fwrite ( $segment . PHP_EOL );
	}
}