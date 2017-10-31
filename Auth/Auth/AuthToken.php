<?php

namespace Aero\Auth;

class AuthToken
{
	protected function csrf(): string
	{
		return $_SESSION[$this -> form -> csrf -> name] = hash_hmac ( 'sha256', random_bytes ( 55 ), $this -> form -> csrf -> key );
	}
}