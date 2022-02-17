<?php

declare ( strict_types = 1 );

set_time_limit ( -1 );

function GoogleSheetsPermissionAdd( string $fileId, string $email, string $role = 'reader' ): void
{
	$client = new Google_Client;

	$client -> useApplicationDefaultCredentials();
	
	$client->setScopes( [
		\Google_Service_Sheets :: SPREADSHEETS, 
		\Google_Service_Drive :: DRIVE, 
	] );

	try
	{
		$service = new Google_Service_Drive( $client );
		
		/* $publicPermission = new Google_Service_Drive_Permission();
		
		$publicPermission -> setEmailAddress( $email );
		
		$publicPermission -> setType( 'user' );
		
		$publicPermission -> setRole( $role ); */
		
		// сохранить идентификатор добавленого пользователя!!!
		/* var_dump ( $service -> permissions -> create( $fileId, $publicPermission, [ 
			'sendNotificationEmail' => false,
		] ) ); */
		
		// вывести список предоставленного доступа
		$permissions = $service -> permissions -> listPermissions( $fileId );
		
		foreach ( $permissions -> getPermissions() AS $list )
		{
			printf ( '"%s",' . PHP_EOL, $list -> getId() );
		}

		
		
		// удалить пользователя по идентификатору
		//$service -> permissions -> delete( $fileId, '00000000000000000000' );
		
		//var_dump ( $service->permissions->listPermissions( $fileId )->getPermissions() );
		
	}
	catch ( \Google\Service\Exception $e )
	{
		var_dump ( $e -> getMessage() );
	}
}


require 'vendor/autoload.php';

putenv ( 'GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/***************************.json' );

GoogleSheetsPermissionAdd( '*********RjdN******oeuJmrtz***7uy****', 'email@gmail.com' );
