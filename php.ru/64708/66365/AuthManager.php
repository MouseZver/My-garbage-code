<?php

namespace Aero\Auth;

use Aero\Auth\Blue\Auth;
use Aero\Application\Main;
use Aero\Database\Lerma;

class AuthManager extends Auth
{
	protected $instance;
	public $form;
	public $isLogged = FALSE;
	public $time;
	public $username;
	public $status;
	
	public function __construct ( Main $Main, string $name )
	{
		$this -> instance = $Main;
		$this -> cookie = 'AuthMe';
		$this -> time = '+ 30 day';
		$this -> username = 'Гость';
		
		$this -> formate( new $name ) -> dropHash();
	}
	protected function isCookie(): bool
	{
		return (bool) filter_input ( INPUT_COOKIE, $this -> cookie, FILTER_VALIDATE_REGEXP, [ 'options' => [ 'regexp' => '/^[a-f0-9]{32}$/' ] ] );
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
	protected function dropHash(): AuthManager
	{
		Lerma::query( [ 'UPDATE %s SET hash = NULL WHERE online <= %d', 
			$this -> form -> table,
			strtotime ( strtr ( $this -> time, '+', '-' ) ) 
		] );
		return $this;
	}
	protected function delCookie()
	{
		setcookie ( $this -> cookie, '', -1, '/' );
	}
}