<?php

error_reporting ( E_ALL );

# Название классов с их пространств имен
const SPACES = [ 
	webog\app\Hello::class, 
	webog\Nub::class, 
	Aero\Application\Purpure\InThisMoment\Lol::class 
];

# Замена пути поиска файла
const NAMESPACESMAP = [ 
	Aero\Application\Purpure\InThisMoment\Lol::class => 'Lol' 
];

# автоподключение файла по требованию в будущем
spl_autoload_register ( function ( $a )
{
	include strtr ( ( NAMESPACESMAP[$a] ?? $a ), '\\', DIRECTORY_SEPARATOR ) . '.php';
} );

# выбираем рандомный класс из 6 строки
$class = SPACES[array_rand ( SPACES )];

# Юзаем
echo new $class . $class;
