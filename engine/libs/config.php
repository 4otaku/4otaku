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
	
	public static function __callStatic($name, $arguments) {

		if (isset(self::$data[$name])) {
			$return = self::$data[$name];
			while ($next = array_shift($arguments)) {
				if (isset($return[$next])) {
					$return = $return[$next];
				} else {
					$return = false;
					break;
				}
			}
		
			return $return;
		}

		trigger_error("Missing config file $name.ini", E_USER_ERROR);
	} 
}
