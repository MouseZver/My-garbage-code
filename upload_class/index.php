<?php

error_reporting ( E_ALL );

if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
	require 'GDImageUp.php';
	
	$G = new GDImageUp( 'pictures' );
	
	if ( $G -> SaveDirectory( 'images/' ) -> SaveName( 'lalka' ) -> SaveFile() === FALSE )
	{
		var_dump ( $G -> ErrorMessage() );
	}
	else
	{
		echo $G;
	}
}

?>
<div style="border: 1px solid red">
<form enctype="multipart/form-data" method="post" action="/index.php">
<input type="file" name="pictures">
<input type="submit" value="Отправить">
</form>
</div>