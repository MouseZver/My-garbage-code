<?php

class GDImageUp
{
	# Максимальный размер файла
	const MAX_SIZE_IMG = ( 1024 * 1024 * 5 );
	
	# Разрешенный тип передаваемого файла
	const MIME_TYPE = [ 'image/png', 'image/jpeg', 'image/gif' ];
	
	private 
		$_mime_type = NULL,
		$_max_size_img,
		$_width = 200,
		$_height = 200,
		$_directory = NULL,
		$_name,
		$_E = [];
	
	public function __construct ( string $KEY, $SIZE = NULL )
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
	public function ImageDirectory( string $DIR )
	{
		$this -> _directory = "{$_SERVER['DOCUMENT_ROOT']}/{$DIR}";
		
		return $this;
	}
	public function ImageName( string $NAME )
	{
		$this -> _name = $NAME;
		
		return $this;
	}
	public function ErrorMessage(): array
	{
		return $this -> _E;
	}
	public function run(): bool
	{
		if ( !file_exists ( $this -> _directory ) )
		{
			$this -> _E[] = 'Invalid is directory';
		}
		if ( count ( array_diff ( $A, self::MIME_TYPE ) ) > 0 )
		{
			$this -> _E[] = 'Denied access Mime type in: ' . implode ( ', ', $D );
		}
		elseif ( !in_array ( $_FILES[$this -> _key]['type'], ( $_MIME_TYPE ?? self::MIME_TYPE ) ) )
		{
			$this -> _E[] = 'Invalid is Mime type File';
		}
		else
		{
			switch ( $_FILES[$this -> _key]['type'] )
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
				$this -> _E[] = 'Invalid image size width/height';
			}
			if ( $this -> _max_size_img < $_FILES[$this -> _key]['size'] )
			{
				$this -> _E[] = 'Invalid File size';
			}
		}
		
		if ( count ( $this -> _E ) > 0 ) return FALSE;
		
		$IMG = ImageCreateTrueColor ( $SX, $SY );
		
		ImageCopyResampled ( $IMG, $IGD, 0, 0, 0, 0, $SX, $SY, $SX, $SY );
		
		switch ( $_FILES[$this -> _key]['type'] )
		{
			case 'image/png':
				$NAME = $this -> _name . '.PNG';
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
		
		ImageDestroy ( $IMG, $IGD );
		
		return $NAME;
	}
}

$G = new GDImageUp( 'pictures', ( 1024 * 1024 * 2 ) );
var_dump ( $G -> setMimeType( 'image/png' ) -> ImageSize( 200, 200 ) -> ImageDirectory( 'upload/' ) -> ImageName( 'lalka' ) -> run() );