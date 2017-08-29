## GDImageUp
Класс для безопасной загрузки изображений
```PHP
class GDImageUp
{
	const MAX_SIZE_IMG
	const MIME_TYPE
	
	public function __construct ( string $string, int $int )
	public function setMimeType( ...$string ): GDImageUp
	public function ImageSize( int $W, int $H ): GDImageUp
	public function SaveDirectory( string $string ): GDImageUp
	public function SaveName( string $string ): GDImageUp
	public function ErrorMessage( void ): array
	public function __toString( void ): string
	private function ErrorCode( int $int ): string
	public function SaveFile( void ): bool
}
```

Методы:

```PHP
GDImageUp::setMimeType( string $string [, string $... ] )
```
Установка типов изображений на допустимость загрузки. Возвращает объект. Если этот метот опущен, то берутся стандартные типы загружаемых файлов.
```PHP
$GD = new GDImageUp
$GD -> setMimeType( 'image/jpeg' ); # Разрешили только тип jpeg
```

```PHP
GDImageUp::ImageSize ( int $W [, int $H ] )
```
Установка максимального разрешенного размера изображения width / height. По дефолту максимальное разрешение загружаемого изображения составляет 200 / 200. Возвращает объект.

```PHP
GDImageUp::SaveDirectory( string $Directory )
```
Сохранение изображений по указанному пути. Возвращает объект.
## Замечание:
Данный метод является одним из важнейших компонентов, при его отсутствии создается ошибка Invalid is directory.

```PHP
GDImageUp::SaveName( string $name )
```
Задает имя загружаемого файла. Возвращает объект.

```PHP
GDImageUp::ErrorMessage()
```
Вывод ошибок.

```PHP
GDImageUp::__toString()
```
Выводит имя файла с его расширением.

```PHP
GDImageUp::SaveFile()
```
Выводит булевое значение TRUE если успешно прошла валидация и сохранение изображения, иначе FALSE.
