<?

final class Config
{
	// Хранилище для загруженных данных
	private static $data = array();
	
	public static function load($file) {
		if (
			!is_readable($file) || 
			pathinfo($file, PATHINFO_EXTENSION) !== 'ini'
		) {
			return false;
		}
		
		$data = parse_ini_file($file, true);
		$name = basename($file, '.ini');
	
		foreach ($data as & $section) {
			if (is_array($section)) {
				foreach ($section as $value_name => $value) {
					if (strpos($value_name, '.')) {
						$name_parts = explode('.', $value_name);					
						$link = & $section;
						
						while ($part = array_shift($name_parts)) {							
							$link = & $link[$part];
						}
						
						$link = $value;
						
						unset($section[$value_name]);
					}
				}
			}
		}
		unset($section);
		
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

		Error::fatal("Missing config file $name.ini");
	} 
}
