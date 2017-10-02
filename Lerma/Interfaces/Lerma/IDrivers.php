<?php

namespace Aero\Interfaces;

interface LermaDrivers
{
	public function query( string $sql ): LermaDrivers;
	public function prepare( string $sql ): LermaDrivers;
	
	# При использовании с постановлением query, выдаст ошибку "Not execute"
	public function execute( array $arguments );
	
	/*
	NUM 	- 1
	ASSOC 	- 2
	BOTH 	- 3
	OBJ 	- 4
	*/
	public function fetch ( int $fetch_style = 3 );
	public function fetchAll( int $fetch_style = 3 );
	public function InsertId(): int;
}