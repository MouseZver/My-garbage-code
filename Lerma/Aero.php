<?php

namespace Aero\test;

class Bar
{
	public function __construct()
	{
		$this -> num = $this -> num + 3;
	}
}
class Aero extends Bar
{
	protected $id;
	
	public function __set( $name, $value )
	{
		$this -> $name = $value;
	}
}