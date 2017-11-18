<?php

error_reporting ( E_ALL );

const SPACES = [ 
	webog\app\Hello::class, 
	webog\Nub::class, 
	Aero\Application\Purpure\InThisMoment\Lol::class 
];

const NAMESPACESMAP = [ 
	Aero\Application\Purpure\InThisMoment\Lol::class => 'Lol' 
];

spl_autoload_register ( function ( $a )
{
	include strtr ( ( NAMESPACESMAP[$a] ?? $a ), '\\', DIRECTORY_SEPARATOR ) . '.php';
} );

$class = SPACES[array_rand ( SPACES )];

echo new $class . $class;