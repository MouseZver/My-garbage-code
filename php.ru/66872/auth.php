<?php

use Aero\Lerma;

if ( Aero::$app -> Auth -> isLogged )
{
	header ( 'Location: /?no' );
	exit;
}

if ( $_SERVER['REQUEST_METHOD'] === 'POST' )
{
	$ARGS = [
		Aero::$app -> Auth -> form -> email => FILTER_VALIDATE_EMAIL,
		Aero::$app -> Auth -> form -> pass => FILTER_DEFAULT,
		Aero::$app -> Auth -> form -> csrf -> name => FILTER_DEFAULT
	];
	
	$error = [];

	$INPUTS = filter_input_array ( INPUT_POST, $ARGS );

	if ( in_array ( NULL, $INPUTS, TRUE ) )
	{
		$error['undefined'] = 'Undefined inputs name :(';
	}
	elseif( !$INPUTS[Aero::$app -> Auth -> form -> email] )
	{
		$error['email'] = 'Invalid is email.';
	}
	elseif ( !isset ( $_SESSION[Aero::$app -> Auth -> form -> csrf -> name] ) || $INPUTS[Aero::$app -> Auth -> form -> csrf -> name] !== $_SESSION[Aero::$app -> Auth -> form -> csrf -> name] )
	{
		$error['token'] = 'Invalid is token.';
	}
	else
	{
		$lerma = Lerma::prepare( [ 'SELECT id, username, password FROM %s WHERE email = ?', Aero::$app -> Auth -> form -> table ], 
			[ strtolower ( $INPUTS[Aero::$app -> Auth -> form -> email] ) ] );
		
		if ( $lerma -> rowCount() === 0 || !password_verify ( $INPUTS[Aero::$app -> Auth -> form -> pass], ( $account = $lerma -> fetch( Lerma::FETCH_OBJ ) ) -> password ) )
		{
			$error['data'] = 'Введенные данные не верны.';
		}
	}
	
	unset ( $_SESSION[Aero::$app -> Auth -> form -> csrf -> name] );
	
	if ( !empty ( $error ) )
	{
		echo implode ( '<br>', $error );
	}
	else
	{
		$password_hash = password_hash ( $INPUTS[Aero::$app -> Auth -> form -> pass], PASSWORD_DEFAULT );
		
		$hash = md5 ( $account -> id . $account -> username . $password_hash );
		
		Lerma::query( [ 'UPDATE %s SET password = "%s", hash = "%s", online = %d WHERE id = %d', 
			Aero::$app -> Auth -> form -> table,
			$password_hash,
			$hash,
			$_SERVER['REQUEST_TIME'],
			$account -> id
		] );
		
		setcookie ( Aero::$app -> Auth -> data -> cookie, $hash, strtotime ( Aero::$app -> Auth -> data -> time ), '/' );
		
		header ( 'Location: /?' . $account -> username );
		exit;
	}
}

require Aero::Separator( '/resources/view/pages/Authform.php' );