<?php

namespace Aero\Auth\Blue;

use Aero\Auth\User;
use Aero\Database\Lerma;

abstract class Auth
{
	abstract protected function isCookie(): bool;
	abstract protected function isLogged(): bool;
	abstract protected function isHash(): bool;
	
	protected function formate( User $User ): Auth
	{
		$this -> form = $User;
		return $this;
	}
	public function runable()
	{
		$this -> isCookie = $this -> isCookie();
		$this -> isHash = $this -> isHash();
		$this -> isLogged = $this -> isLogged();
	}
	protected function hash(): bool
	{
		return Lerma::query( [ 'SELECT id FROM %s WHERE hash = "%s"', 
			$this -> form -> table,
			$_COOKIE[$this -> cookie]
		] ) -> rowCount() == 1;
	}
	protected function AuthMe(): bool
	{
		if ( isset ( $_SESSION['logged'] ) )
		{
			$account = Lerma::query( [ 'SELECT id, username, status, password FROM %s WHERE id = %d', 
				$this -> form -> table,
				$_SESSION['id'] 
			] ) -> fetch( Lerma::FETCH_OBJ );
			
			if ( !$this -> isHash )
			{
				$hash = md5 ( $account -> id . $account -> username . $account -> password );
				
				Lerma::query( [ 'UPDATE %s SET online = %d, hash = "%s" WHERE id = %d', 
					$this -> form -> table,
					$_SERVER['REQUEST_TIME'],
					$hash,
					$account -> id 
				] );
				
				setcookie ( $this -> cookie, $hash, strtotime ( $this -> time ), '/' );
			}
		}
		elseif ( $this -> isHash )
		{
			$account = Lerma::query( [ 'SELECT id, username, status FROM %s WHERE hash = "%s"', 
				$this -> form -> table,
				$_COOKIE[$this -> cookie]
			] ) -> fetch( Lerma::FETCH_OBJ );
		}
		else
		{
			$this -> delCookie();
			return FALSE;
		}
		
		if ( $this -> isHash )
		{
			Lerma::query( [ 'UPDATE %s SET online = %d WHERE id = %d', 
				$this -> form -> table,
				$_SERVER['REQUEST_TIME'],
				$account -> id 
			] );
		}
		
		$_SESSION['id'] = $account -> id;
		$_SESSION['logged'] = TRUE;
		$this -> username = $_SESSION['username'] = $account -> username;
		$this -> status = (int)$_SESSION['status'] = $account -> status;
		
		return TRUE;
	}
	protected function setSess(): bool
	{
		if ( $this -> isHash )
		{
			$this -> username = $_SESSION['username'];
			$this -> status = (int)$_SESSION['status'];
			
			return TRUE;
		}
		else
		{
			return $this -> AuthMe();
		}
	}
}