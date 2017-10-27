<?php

error_reporting ( E_ALL );

use Aero\Database\Lerma AS Lerma;

spl_autoload_register ( function ( $name )
{
	$LermaSpace = [
		Aero\Configures\Lerma::class		=> 'Configures\Lerma',
		Aero\Database\Lerma::class			=> 'Database\Lerma',
		Aero\Database\Migrate::class		=> 'Database\Migrate',
		Aero\Interfaces\LermaDrivers::class	=> 'Interfaces\Lerma\IDrivers',
		Aero\test\Aero::class				=> 'Aero'
	];
	
	include strtr ( ( $LermaSpace[$name] ?? $name ), '\\', DIRECTORY_SEPARATOR ) . '.php';
} );

/*
	Мульти заправка таблицы данными с n - строк
	
	[ 
		[  ], - 1
		[  ], - 2
		...   - n
	]
*/
/* Lerma::prepare( 'INSERT INTO lerma ( name, num ) VALUES ( ?,? )', [
	1 => [ 'Aero', 111 ],
	2 => [ 'Lerma', 111 ],
	3 => [ 'Migrate', 111 ],
	4 => [ 'Database', 111 ],
	5 => [ 'Configures', 222 ],
	6 => [ 'Interfaces', 333 ],
	7 => [ 'LermaDrivers', 333 ],
] ); */

Lerma::query( [ 'UPDATE lerma SET name = "%s" WHERE id = 1', quotemeta ( Aero\test\Aero::class ) ] );

print_r ( Lerma::query( 'SELECT name, id, num FROM lerma LIMIT 1' ) -> fetchAll( Lerma::FETCH_CLASSTYPE | Lerma::FETCH_UNIQUE, [ true, true ] ) );



/* fetch()
	FETCH_NUM
	FETCH_ASSOC
	FETCH_OBJ
	FETCH_BIND
	FETCH_BIND | FETCH_COLUMN
	FETCH_COLUMN
	FETCH_KEY_PAIR
	FETCH_FUNC
	FETCH_CLASS
	FETCH_CLASSTYPE

fetchAll()
	FETCH_NUM
	FETCH_ASSOC
	FETCH_OBJ
	FETCH_COLUMN
	FETCH_KEY_PAIR
	FETCH_KEY_PAIR | FETCH_NAMED
	FETCH_UNIQUE
	FETCH_GROUP
	FETCH_GROUP | FETCH_COLUMN
	FETCH_FUNC
	FETCH_CLASS
	FETCH_CLASSTYPE
	Lerma::FETCH_CLASSTYPE | Lerma::FETCH_UNIQUE*/



/* 
Обновляем кое что...
Lerma::query( [ 'UPDATE lerma SET name = "%s" WHERE id = 1', quotemeta ( Aero\test\Aero::class ) ] );

Создаем наш тестовый класс... с наследовательностью. Aero.php
[PHP]
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
[/PHP]

Присваивает результат свойствам заданного класса, после запускает конструктор.
[PHP]
print_r ( Lerma::query( 'SELECT name, id, num FROM lerma LIMIT 3' ) -> fetchAll( Lerma::FETCH_CLASS, Aero\test\Aero::class ) );
[/PHP]
[CODE]
Array
(
    [0] => Aero\test\Aero Object
        (
            [id:protected] => 1
            [name] => Aero\test\Aero
            [num] => 114
        )

    [1] => Aero\test\Aero Object
        (
            [id:protected] => 2
            [name] => Lerma
            [num] => 114
        )

    [2] => Aero\test\Aero Object
        (
            [id:protected] => 3
            [name] => Migrate
            [num] => 114
        )
)
[/CODE]

Так же но, наименование класса берется с первой колонки. С остальных присваивает свойствам результат.
[PHP]
print_r ( Lerma::query( 'SELECT name, id, num FROM lerma LIMIT 1' ) -> fetchAll( Lerma::FETCH_CLASSTYPE ) );
[/PHP]
[CODE]
Array
(
    [0] => Aero\test\Aero Object
        (
            [id:protected] => 1
            [num] => 114
        )
)
[/CODE]

Добавление Юникью константы приводит еще к записи наименования класса в индекс результата
[PHP]
print_r ( Lerma::query( 'SELECT name, id, num FROM lerma LIMIT 1' ) -> fetchAll( Lerma::FETCH_CLASSTYPE | Lerma::FETCH_UNIQUE ) );
[/PHP]
[CODE]
Array
(
    [Aero\test\Aero] => Aero\test\Aero Object
        (
            [id:protected] => 1
            [num] => 114
        )
)
[/CODE]

Если задать второй параметр true, в индекс присвоиться лишь само имя класса
[CODE]
Array
(
    [Aero] => Aero\test\Aero Object
        (
            [id:protected] => 1
            [num] => 114
        )
)
[/CODE]


[PHP]

[/PHP]
[CODE]

[/CODE]
 */