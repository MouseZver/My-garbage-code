<?php

error_reporting ( E_ALL );

use Aero\Supports\Lerma;

require 'vendor/autoload.php';

if ( $_SERVER['REQUEST_METHOD'] === 'POST' )
{
	$task = filter_input ( INPUT_POST, 'task', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE ) or die ( '<p>Значение пустое</p>' );
	
	Lerma::prepare( 'INSERT INTO `tasks`( `task` ) VALUES ( ? )', [ $task ] );
}
elseif ( $id = filter_input ( INPUT_GET, 'del_task', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE ) )
{
	Lerma::query( [ 'DELETE FROM `tasks` WHERE id = %d', $id ] );
}



$lerma = Lerma::query( 'SELECT * FROM `tasks`' );

?>
 
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лист задач с PHPMyAdmin</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="heading">
        <h2>Лист задач используя MySQL</h2>
    </div>
 
    <form action="/index.php" method="POST">
        <input type="text" name="task" class="task_input">
        <button type="submit" class="add_btn" name="submit">Добавить задание</button>
    </form>
<? if ( $lerma -> rowCount() ) { ?>
    <table>
        <thead>
            <tr>
                <th>N</th>
                <th>Task</th>
                <th>Action</th>
            </tr>
        </thead>
 
        <tbody>
        <?
			$i = 1;
			
			echo implode ( PHP_EOL, $lerma -> fetchAll( Lerma::FETCH_FUNC, function ( ...$row ) use ( &$i )
			{
				return sprintf ( '<tr><td>%d</td><td class="task">%s</td><td class="delete"><a href="/index.php?del_task=%d">x</a></td></tr>', $i++, $row[1], $row[0] );
			} ) );
		?>
        </tbody>
 
    </table>
<? } ?>
</body>
</html>