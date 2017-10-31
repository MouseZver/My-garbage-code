<?php

namespace Aero\Configures;

final class Auth
{
	public $cookie = 'AuthMe';
	public $time = '+ 30 day';
	public $username = 'Гость';
	public $form = '{
		"table":"usraccount",
		"name":"username",
		"email":"email",
		"pass":"password",
		"confirm":"confirm_password",
		"csrf":{
			"name":"aerotoken",
			"key":"AeroPurpure"
		}
	}';
}