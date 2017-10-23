<?php

namespace Aero\Interfaces;

interface LermaDrivers
{
	public function query	( string $sql ): LermaDrivers;
	public function prepare	( string $sql ): LermaDrivers;
	public function execute	( array $arguments );
	public function fetch	( int $fetch_style );
	public function fetchAll( int $fetch_style );
	public function InsertId(): int;
}