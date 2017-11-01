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
		Aero::$app -> Auth -> form -> name => [ 
			'filter' => FILTER_VALIDATE_REGEXP,
			'options' => [
				'regexp' => '/^[A-Za-z0-9_-]{3,25}$/'
			]
		],
		Aero::$app -> Auth -> form -> email => FILTER_VALIDATE_EMAIL,
		Aero::$app -> Auth -> form -> pass => FILTER_DEFAULT,
		Aero::$app -> Auth -> form -> confirm => FILTER_DEFAULT,
		Aero::$app -> Auth -> form -> csrf -> name => FILTER_DEFAULT
	];
	
	$error = [];
	
	$INPUTS = filter_input_array ( INPUT_POST, $ARGS );
	
	if ( in_array ( NULL, $INPUTS, TRUE ) )
	{
		$error['undefined'] = 'Undefined inputs name :(';
	}
	elseif ( !isset ( $_SESSION[Aero::$app -> Auth -> form -> csrf -> name] ) || $INPUTS[Aero::$app -> Auth -> form -> csrf -> name] !== $_SESSION[Aero::$app -> Auth -> form -> csrf -> name] )
	{
		$error['token'] = 'Invalid is token.';
	}
	else
	{
		if ( !$INPUTS[Aero::$app -> Auth -> form -> name] ) 
		{
			$error['username'] = 'Пожалуйста, введите имя, содержащее от 3-х до 25 латинских символов.';
		}
		if ( !$INPUTS[Aero::$app -> Auth -> form -> email] ) 
		{
			$error['email'] = 'Адрес электронной почты должен быть валидным.';
		}
		if ( empty ( $INPUTS[Aero::$app -> Auth -> form -> pass] ) )
		{
			$error['password'] = 'Пожалуйста, введите пароль.';
		}
		elseif ( $INPUTS[Aero::$app -> Auth -> form -> pass] != $INPUTS[Aero::$app -> Auth -> form -> confirm] )
		{
			$error['password'] = 'Введенные пароли не совпадают.';
		}
		
		if ( !isset ( $error['username'] ) && Lerma::prepare( [ 'SELECT id FROM %s WHERE lower ( username ) = ?', Aero::$app -> Auth -> form -> table ], 
			[ strtolower ( $INPUTS[Aero::$app -> Auth -> form -> name] ) ] ) -> rowCount() > 0 )
		{
			$error['username'] = 'Введенное имя уже используется.';
		}
		
		if ( !isset ( $error['email'] ) && Lerma::prepare( [ 'SELECT id FROM %s WHERE email = ?', Aero::$app -> Auth -> form -> table ], 
			[ strtolower ( $INPUTS[Aero::$app -> Auth -> form -> email] ) ] ) -> rowCount() > 0 )
		{
			$error['email'] = 'Введенная почта уже используется.';
		}
		elseif ( empty ( $error ) && !mail ( $INPUTS[Aero::$app -> Auth -> form -> email], 'Aero register user Account', sprintf ( 'The %s registration complecte.', $INPUTS[Aero::$app -> Auth -> form -> name] ) ) )
		{
			$error['email'] = 'Ошибка с введенной почтой';
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
		
		Lerma::prepare( [ 'INSERT INTO %s ( username, email, password, datareg, online ) VALUES ( ?,?, "%s", %d, %3$d )', 
			Aero::$app -> Auth -> form -> table,
			$password_hash,
			$_SERVER['REQUEST_TIME']
		], [ 
			$INPUTS[Aero::$app -> Auth -> form -> name], 
			strtolower ( $INPUTS[Aero::$app -> Auth -> form -> email] )
		] );
		
		$hash = md5 ( Lerma::InsertId() . $INPUTS[Aero::$app -> Auth -> form -> name] . $password_hash );
		
		Lerma::query( [ 'UPDATE %s SET hash = "%s" WHERE id = %d', Aero::$app -> Auth -> form -> table, $hash, Lerma::InsertId() ] );
		
		setcookie ( Aero::$app -> Auth -> data -> cookie, $hash, strtotime ( Aero::$app -> Auth -> data -> time ), '/' );
		
		header ( 'Location: /?' . $INPUTS[Aero::$app -> Auth -> form -> name] );
		exit;
	}
}

require Aero::Separator( '/resources/view/pages/Regform.php' );