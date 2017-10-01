<?php

error_reporting ( E_ALL | E_STRICT );

require ( dirname ( __FILE__, 3 ) . '/Interfaces/Lerma/IDrivers.php' );
require ( dirname ( __FILE__, 3 ) . '/Configures/Lerma.php' );

$Lerma = new Aero\Configures\Lerma;

$a = require $Lerma -> driver . '.php';

$s = $a -> prepare( 'SELECT * FROM usraccount WHERE id = ?' ) -> execute( [ 5 ] ) -> rowCount();

var_dump($s);



/*
PDO::FETCH_ASSOC 2 MYSQLI_NUM
PDO::FETCH_NUM 3 MYSQLI_BOTH
PDO::FETCH_BOTH 4
PDO::FETCH_OBJ 5
MYSQLI_ASSOC 1

pdo[
	1 => PDO::FETCH_NUM,
	2 => PDO::FETCH_ASSOC,
	3 => PDO::FETCH_BOTH,
	4 => PDO::FETCH_OBJ
]
MySQLi[
	1 => MYSQLI_NUM
	2 => MYSQLI_ASSOC
	3 => MYSQLI_BOTH
	4 => 4
]
*/
