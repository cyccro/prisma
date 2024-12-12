<?php



enum FileOptions
{
	case Read;
	case Write;
	case ReadWrite;
	case ReadWriteTrunc;
	public function as_str(): string
	{
		return match ($this) {
			self::Read => "r",
			self::Write => "w",
			self::ReadWrite => "r+",
			self::ReadWriteTrunc => "w+",
		};
	}
}
enum FileSysError
{
	case NotFound;
	case PermissionDenied;
	case IsntFile;
	case FileExists;
	case ReadonlyFile;
	public function err(): ErrorException
	{
		return new ErrorException(match ($this) {
			self::NotFound => "File not found",
			self::FileExists => "File already exists",
			self::ReadonlyFile => "Trying to write on a readonly opened file",
			self::PermissionDenied => "Tried to open file but hasn't enough permissions",
			self::IsntFile => "Tried to open a directory as file",
		});
	}
}
class File
{
	//check FileSys::open_file for details
	public static function open(string $path): self
	{
		$f = FileSys::open_file($path);
		return new self($f);
	}
	//check FileSys::get_file for details
	public static function get(string $path): self
	{
		$f = FileSys::get_file($path);
		return new self($f);
	}
	//check FileSys::create_file for details
	public static function create(string $path): self
	{
		$f = FileSys::create_file($path);
		return new self($f);
	}
	public function __construct(private $file) {}
	public function __destruct()
	{
		fclose($this->file);
	}
	public function content(): string
	{
		$fsize = fstat($this->file)["size"];
		$content = fread($this->file, $fsize);
		return $content;
	}
	public function write(string $data, $len = -1): int
	{
		$len == -1 ? $len = mb_strlen($data, "UTF-8") : 0;
		return fwrite($this->file, $data, $len) || 0;
	}
	public function truncate(int $len)
	{
		return ftruncate($this->file, $len);
	}
	//copies the content of this file to the given path, if it exists, truncates and overwrite, if not, creates the file and copies
	public function copy(string $path)
	{
		$f = self::create(FileSys::get_file($path));
		$f->write($this->content());
		return $f;
	}
}
class FileSys
{
	//creates a file and returns it with read write permission. Throws it if already exists
	static function create_file(string $path)
	{
		if (file_exists($path)) throw FileSysError::FileExists->err();
		return fopen($path, "r+");
	}
	//creates a file if it does not exist, if exists and throws = true, throws, else, it will truncate
	static function create_empty(string $path, bool $throws = true)
	{
		$fexist = file_exists($path);
		if ($fexist && $throws) throw FileSysError::FileExists->err();
		return fopen($path, "w+");
	}
	//opens a file with read only permission, Throws if it doesn't exist
	static function open_file(string $path)
	{
		try {
			$f = fopen($path, "r");
			return $f;
		} catch (ErrorException $_) {
			throw FileSysError::NotFound->err();
		}
	}
	//opens read write file if it exists, else creates and opens read write
	static function get_file(string $path)
	{
		if (file_exists($path)) return fopen($path, "r+");
		else return fopen($path, "w+");
	}
	private function __construct()
	{
		throw new ErrorException("Invalid Constructor: FileSystem");
	}
}
