<?php

namespace Aero\Supports;

use Aero\Lerma;
use Aero\Auth\Authorization;

final class Auth extends Authorization
{
	public $form;
	public $isLogged;
	public $time;
	protected $username;
	public $status;
	public $data;
	
	public function __construct ( string $name ) # Aero\Configures\Auth::class
	{
		$this -> form = json_decode ( ( $c = new $name ) -> form );
		
		unset ( $c -> form );
		
		$this -> dropHash() -> data = $c;
	}
	public function __get( $name )
	{
		if ( $name === 'username' )
		{
			return $this -> username ?? $this -> data -> username;
		}
		
		throw new \Exception( '~~ Смотри трейс #1, инвалид пришел к нам: ' . $name );
	}
	protected function isCookie(): bool
	{
		return (bool) filter_input ( INPUT_COOKIE, $this -> data -> cookie, FILTER_VALIDATE_REGEXP, [ 'options' => [ 'regexp' => '/^[a-f0-9]{32}$/' ] ] );
	}
	protected function isLogged(): bool
	{
		if ( isset ( $_SESSION['logged'] ) )
		{
			return $this -> setSess();
		}
		
		if ( $this -> isHash )
		{
			return $this -> AuthMe();
		}
		elseif ( $this -> isCookie )
		{
			$this -> delCookie();
		}
		
		return FALSE;
	}
	protected function isHash(): bool
	{
		if ( $this -> isCookie )
		{
			return $this -> hash();
		}
		
		return FALSE;
	}
	protected function dropHash(): Auth
	{
		Lerma::query( [ 'UPDATE %s SET hash = NULL WHERE online <= %d', 
			$this -> form -> table,
			strtotime ( strtr ( $this -> data -> time, '+', '-' ) ) 
		] );
		return $this;
	}
	protected function delCookie()
	{
		setcookie ( $this -> data -> cookie, '', -1, '/' );
	}
}