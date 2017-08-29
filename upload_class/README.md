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
	public function ErrorMessage(): array
	public function __toString(): string
	private function ErrorCode( int $int ): string
	public function SaveFile(): bool
}
```

Методы:
- ```PHP GDImageUp::setMimeType( string $string [, string $... ] ) ``` — Установка типов изображений на допустимость загрузки. Возвращает объект 
```PHP
$GD = new GDImageUp
$GD -> setMimeType( 'image/jpeg' ); # Разрешили только тип jpeg
```
Замечание: Все перечисления ведутся через запятую в string формате.

- GDImageUp::
