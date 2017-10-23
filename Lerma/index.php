<?php

error_reporting ( E_ALL );

use Aero\Database\Lerma AS Lerma;

spl_autoload_register ( function ( $name )
{
	$LermaSpace = [
		Aero\Configures\Lerma::class		=> 'Configures\Lerma',
		Aero\Database\Lerma::class			=> 'Database\Lerma',
		Aero\Database\Migrate::class		=> 'Database\Migrate',
		Aero\Interfaces\LermaDrivers::class	=> 'Interfaces\Lerma\IDrivers'
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
Lerma::prepare( 'INSERT INTO lerma ( name ) VALUES ( ? )', [[ 'aaaa' ], [ 'dgdf' ], [ 'awefaw' ], [ 'dszvdszfsf' ], [ 'd' ], [ 'rrrrr' ]] );

# Познаем крайний индентификатор
echo Lerma::InsertID() . PHP_EOL;



# пакуем весь результат, запрашивая все айдишки с возвратом числовым индексом
$ids = Lerma::query( 'SELECT id FROM lerma' ) -> fetchAll( Lerma::FETCH_NUM );

# Можно и фантазией пошалить
$r = Lerma::prepare( [ 'SELECT name FROM %s WHERE id BETWEEN ? AND ?', 'lerma' ], [ 10,20 ] );
#echo $r -> fetchColumn();


/*
	Господа: прошлый запрос завис на сервере. В результате новенького запроса, старенький уйдет на покой автоматом.
	
	Биндим результат напрямую с сервера
*/
$lerma = Lerma::prepare( [ 'SELECT CONCAT ( id, " %2$X ", name ) FROM %s WHERE id BETWEEN ? AND ?', 'lerma' , 666 ], [ 1,12 ] );

while ( $concat = $lerma -> fetch( Lerma::FETCH_BIND | Lerma::FETCH_COLUMN ) )
{
	/* ..алилуя.. */
	echo $concat . PHP_EOL;
}

$a = Lerma::query( 'SELECT id FROM lerma LIMIT 5' ) -> fetchAll( Lerma::FETCH_COLUMN );
$b = Lerma::query( 'SELECT id FROM lerma LIMIT 5' ) -> fetchAll( Lerma::FETCH_NUM );

var_dump($a,$b);