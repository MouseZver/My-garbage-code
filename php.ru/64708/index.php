<?

if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
	$ARGS = [
		'group1w' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
		'name' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
		'phone' => FILTER_SANITIZE_NUMBER_INT,
		'email' => FILTER_VALIDATE_EMAIL,
		'select' => FILTER_SANITIZE_NUMBER_INT
	];
	
	$INPUTS = filter_input_array ( INPUT_POST, $ARGS );
	
	$E = [];
	
	if ( in_array ( NULL, $INPUTS ) )
	{
		$E['undefined'] = 'Undefined inputs :(';
	}
	if ( in_array ( FALSE, [ $INPUTS['group1w'], $INPUTS['name'] ] ) )
	{
		$E['name'] = 'Пожалуйста, введите имя, содержащее OLOLO.';
	}
	if ( $INPUTS['email'] === NULL )
	{
		$E['email'] = 'Undefined email :(';
	}
	elseif ( $INPUTS['email'] === FALSE )
	{
		$E['email'] = 'Адрес электронной почты должен быть валидным.';
	}
	if ( $INPUTS['select'] === NULL )
	{
		$E['select'] = 'Undefined select :(';
	}
	elseif ( $INPUTS['select'] === FALSE )
	{
		$E['select'] = 'Выберите код телефона.';
	}
	
	if ( count ( $E ) > 0 )
	{
		printf ( '<div style="color:red">%s</div>', implode ( '<br>', $E ) );
	}
	else
	{
		exit ( ( mail ( 'vladprofmet@gmail.com', 'Заказ на магазин', implode ( '<br>', $INPUTS ) ) ? 'Заявка успешно оформлена' : 'Ошибка' ) );
	}
}
