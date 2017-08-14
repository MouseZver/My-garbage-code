<?php

error_reporting ( E_ALL );

interface FilesUp
{
	public function SaveDirectory( string $DIRECTORY );	# Директория для сохранения файла
	public function SaveFile(): bool;				# Функция валидации и сохранения файла
	public function FileName(): string;				# Вывод имя файла с расширением
	public function ErrorMessage(): array;			# Вывод ошибок
}

class GDImageUp implements FilesUp
{
	# Максимальный размер файла
	const MAX_SIZE_IMG = ( 1024 * 1024 * 5 );
	
	# Разрешенный тип передаваемого файла
	const MIME_TYPE = [ 'image/png', 'image/jpeg', 'image/gif' ];
	
	private 
		$_mime_type = [],
		$_max_size_img,
		$_width = 200,
		$_height = 200,
		$_directory = NULL,
		$_name,
		$_E = [];
	
	public function __construct ( string $KEY, $SIZE = FALSE )
	{
		$this -> _key = $KEY;
		$this -> _name = basename ( $_FILES[$KEY]['name'] );
		$this -> _max_size_img = ( $SIZE ?? self::MAX_SIZE_IMG );
	}
	public function setMimeType( ...$A )
	{
		$this -> _mime_type = $A;
		
		return $this;
	}
	public function ImageSize( int $W, int $H )
	{
		$this -> _width = $W;
		$this -> _height = $H;
		
		return $this;
	}
	public function SaveDirectory( string $DIR )
	{
		$this -> _directory = "{$_SERVER['DOCUMENT_ROOT']}/{$DIR}";
		
		return $this;
	}
	public function SaveName( string $NAME )
	{
		$this -> _name = $NAME;
		
		return $this;
	}
	public function ErrorMessage(): array
	{
		return $this -> _E;
	}
	public function FileName(): string
	{
		return $this -> _name;
	}
	private function ErrorCode( int $A ): string
	{
		return [
			0 => FALSE,
			2 => FALSE,
			UPLOAD_ERR_INI_SIZE => 'Размер принятого файла превысил максимально допустимый размер.',
			UPLOAD_ERR_PARTIAL => 'Загружаемый файл был получен только частично.',
			UPLOAD_ERR_NO_FILE => 'Файл не был загружен.',
			UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка.',
			UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
			UPLOAD_ERR_EXTENSION => 'PHP - расширение остановило загрузку файла'
		][$A];
	}
	public function SaveFile(): bool
	{
		if ( !isset ( $_FILES[$this -> _key] ) )
		{
			$this -> _E['undefined'] = 'Undefined input name in:' . $this -> _key;
			
			return FALSE;
		}		
		if ( !empty ( $this -> ErrorCode( $_FILES[$this -> _key]['error'] ) ) )
		{
			$this -> _E['message'] = $this -> ErrorCode( $_FILES[$this -> _key]['error'] );
			
			return FALSE;
		}
		
		$MIME = explode ( ';', ( new finfo ( FILEINFO_MIME ) ) -> file( $_FILES[$this -> _key]['tmp_name'] ) )[0];
		
		if ( !file_exists ( $this -> _directory ) )
		{
			$this -> _E['dir'] = 'Invalid is directory';
		}
		if ( count ( array_diff ( $this -> _mime_type, self::MIME_TYPE ) ) > 0 )
		{
			$this -> _E['property_type'] = 'Denied access Mime type in: ' . implode ( ', ', $D );
		}
		elseif ( !in_array ( $MIME, ( $this -> _mime_type ?: self::MIME_TYPE ) ) )
		{
			$this -> _E['type'] = 'Invalid Mime type File';
		}
		else
		{
			switch ( $MIME )
			{
				case 'image/png':
					$IGD = ImageCreateFromPNG ( $_FILES[$this -> _key]['tmp_name'] );
				break;
				case 'image/jpeg':
					$IGD = ImageCreateFromJPEG ( $_FILES[$this -> _key]['tmp_name'] );
				break;
				case 'image/gif':
					$IGD = ImageCreateFromGIF ( $_FILES[$this -> _key]['tmp_name'] );
				break;
			}
			
			$SX = ImageSX ( $IGD );
			$SY = ImageSY ( $IGD );
			
			if ( $this -> _width < $SX || $this -> _height < $SY )
			{
				$this -> _E[] = 'Invalid image size';
			}
			if ( $this -> _max_size_img < $_FILES[$this -> _key]['size'] )
			{
				$this -> _E[] = 'Invalid File size';
			}
		}
		
		if ( count ( $this -> _E ) > 0 ) return FALSE;
		
		$IMG = ImageCreateTrueColor ( $SX, $SY );
		
		ImageCopyResampled ( $IMG, $IGD, 0, 0, 0, 0, $SX, $SY, $SX, $SY );
		
		switch ( $MIME )
		{
			case 'image/png':
				$NAME = $this -> _name . '.png';
				ImagePNG ( $IMG, $this -> _directory . DIRECTORY_SEPARATOR . $NAME );
			break;
			case 'image/jpeg':
				$NAME = $this -> _name . '.jpg';
				ImageJPEG ( $IMG, $this -> _directory . DIRECTORY_SEPARATOR . $NAME );
			break;
			case 'image/gif':
				$NAME = $this -> _name . '.gif';
				ImageGIF ( $IMG, $this -> _directory . DIRECTORY_SEPARATOR . $NAME );
			break;
		}
		
		$this -> _name = $NAME;
		
		ImageDestroy ( $IMG );
		
		return TRUE;
	}
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
	$G = new GDImageUp( 'pictures', ( 1024 * 1024 * 2 ) );
	
	if ( $G -> ImageSize( 2000, 2000 ) -> SaveDirectory( 'upload/' ) -> SaveName( 'lalka' ) -> SaveFile() === FALSE )
	{
		var_dump ( $G -> ErrorMessage() );
	}
	echo $G -> FileName();
}
?>
<form enctype="multipart/form-data" method="post" action="/Upload.php">
<input type="file" name="pictures">
<input type="submit" value="ooooo">
</form>
