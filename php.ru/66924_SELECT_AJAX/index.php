<html>
	<head>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script>
		$(function ()
		{
			$( 'body' ).on( 'change', '#Mouse', function ()
			{
				var id = $( this ).val();
				
				$.ajax({
					url: '/' + id + '.php',
					success: function( e )
					{
						alert( e );
					}
				});
			});
		});
		</script>
	</head>
	<body>
		<select id="Mouse">
			<option value="1">Обработать скрипт 1</option>
			<option value="2">Обработать скрипт 2</option>
		</select>
	</body>
</html>