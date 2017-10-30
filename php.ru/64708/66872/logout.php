<?php

use Aero\Lerma;

if ( Aero::$app -> Auth -> isLogged )
{
	Lerma::query( [ 'UPDATE %s SET hash = NULL WHERE id = %d', 
		Aero::$app -> Auth -> form -> table,
		$_SESSION['id']
	] );
	
	setcookie ( Aero::$app -> Auth -> data -> cookie, '', -1, '/' );
	
	session_destroy ();
}

header ( 'Location: /' );
exit;