## Lerma
Инструмент для выполнения sql запросов с различными драйверами connect is Database
***
### Installation:
***
Скопируйте библиотеку Lerma в папку вашего фреймворка \ ядро.
Отредактируйте или создайте условия для пространств имен.
#### NameSpaces:
```PHP
const NAMESPACESMAP = [
	'Lerma\Interfaces\LermaDrivers' => 'Lerma\Interfaces\Lerma\IDrivers'
];

function autoload( string $name )
{
	if ( isset ( NAMESPACESMAP[$name] ) )
	{
		$name = NAMESPACESMAP[$name];
	}
	
	/* ... */
}
```
***
### Настройка:
***
Все настраиваемые элементы находятся в файле Lerma.php, каталог Configures
```PHP
class Lerma
{
	private const USER = 'root';
	private const PASSWORD = '';
	
	# Назначение драйвера для подключения базы данных
	public $driver = 'mysqli';
	
	# Класс для обработки запроса
	public $migrate = 'mysql';
	
	# Параметры для драйвера mysqli
	public $mysqli = [
		'host' => '127.0.0.1',
		'user' => self::USER,
		'password' => self::PASSWORD,
		'dbname' => 'single',
		'port' => 3306
	];
	
	# Параметры для драйвера PDO
	public $pdo = [
		'dns' => 'mysql:host=127.0.0.1;dbname=single;charset=utf8',
		'user' => self::USER,
		'password' => self::PASSWORD,
		'options' => [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_EMULATE_PREPARES => FALSE,
		]
	];
};
```
- public $driver ( наименование подключаемых драйверов mysqli or pdo )
- public $migrate ( не реализован... )
- public $mysqli, $pdo ( опции настройки самих расширений )
###### Замечание: настройки должны следовать в последовательном порядке. Не допустим рандом параметров.
***
### Актуально:
***
На данный момент актуальны следующие методы Лермы:
```PHP
Lerma::prepare( sql, [ params ] );

Lerma::query( sql );
```
***
### Использование всевозможностей:
***
```PHP
<?php

use Lerma\Database AS Lerma;

# Простой, подготовленный запрос
$statement = Lerma::prepare( 'SELECT `id`, `name`, `hash` FROM `table` WHERE `column` = ?', [ 'mail@example.ru' ] );

if ( $statement -> rowCount() > 0 )
{
	foreach ( $statement -> fetchAll( Lerma::FETCH_OBJ ) AS [ $id, $name, $hash ] )
	/* ... */
}

# ---

# Простой обыкновенный запрос
$statement = Lerma::query( 'SELECT `name`, `hash` FROM `table` WHERE `id` = ' . 5 );

if ( $statement -> rowCount() > 0 )
{
	[ $name, $hash ] = $statement -> fetch( Lerma::FETCH_NUM );
	/* OR */
	extract ( $statement -> fetch( Lerma::FETCH_ASSOC ) );
}

# ---

# Так же возможно использовать в подготовленных и обыкновенных запросов для внутреннего форматирования функцией sprintf
$table = 'table';
$id = 666;
$email = 'mail@example.ru';

$statement = Lerma::query( [ 'SELECT `name`, `hash` FROM `%s` WHERE `id` = %d', $table, $id ] );

$statement = Lerma::query( [ 'SELECT `name`, `hash` FROM `%s` WHERE `id` IN ( %s )', $table, implode ( ', ', [ 1,2,5,88 ] ) ] );

$statement = Lerma::prepare( 
	[ 'SELECT `id`, `name`, `hash` FROM `%s` WHERE `column` = ?', $table ],
	[ $email ]
);
```
***
### Константы ( стандартные ):
***
- Lerma::FETCH_NUM
- Lerma::FETCH_ASSOC
- Lerma::FETCH_BOTH
- Lerma::FETCH_OBJ
###### Замечание: возвращает результат в BOTH параметре без использования одной из перечисленных констант.
***
### Синтаксис:
***
```PHP
Lerma::prepare( [ ..., ... ], [ params... ] ) -> fetch();

# dev...
$Lerma = Lerma::prepare( [ 'INSERT INTO...', ... ], [ [ params... ], [ params... ], ...multi ] );
$Lerma -> InsertId();
```
