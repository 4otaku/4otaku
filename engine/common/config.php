<?

final class Config
{
	// Хранилище для загруженных данных
	public static $data = array();
	
	public static function load($file) {
		if (
			!is_readable($file) || 
			pathinfo($file, PATHINFO_EXTENSION) !== 'ini'
		) {
			return false;
		}
		
		$data = parse_ini_file($file, true);
		$name = basename($file, '.ini');		
		self::$data[$name] = $data;
		
		return true;
	}
}