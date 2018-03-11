<?php

error_reporting ( E_ALL );

use Aero\Supports\Lerma;

require __DIR__ . '/vendor/autoload.php';

$lerma = Lerma::query( 'SELECT * FROM lerma' );

$a = [ '<table>', sprintf ( '<tr><td><b>%s</b></td></tr>', implode ( '</b></td><td><b>', $lerma -> fetchAll( Lerma::FETCH_FIELD, 'name' ) ) ) ];

$a += $lerma -> fetchAll( Lerma::FETCH_FUNC, function ( ...$items )
{
	return sprintf ( '<tr><td>%s</td></tr>', implode ( '</td><td>', $items ) );
} );

echo implode ( PHP_EOL, ( $a += [ '</table>' ] ) );