<?

final class Config
{
	// Хранилище для загруженных данных
	private static $data = array();
	
	// Ссылки на найденные файлы настроек
	private static $links = array();	
	
	// Доступ на чтение, но не на запись
	
	public static function add_file($file) {

		$name = basename($file, '.ini');
		
		self::$links[$name] = $file;
	} 
	
	public static function load($file, $module_config = false, $main_module = false) {
		if (
			!is_readable($file) || 
			pathinfo($file, PATHINFO_EXTENSION) !== 'ini'
		) {
			return false;
		}
	
		$data = parse_ini_file($file, true);
		
		if ($module_config == false) {
			$name = basename($file, '.ini');
		} else {
			$name = basename(rtrim(dirname($file), SL));
			if ($main_module != false) {
				self::$data['settings'] = & self::$data[$name];
			}
		}
		
		self::$links[$name] = $file;
	
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
	
	public static function update() {
		
		$arguments = func_get_args();		
		
		$value = array_pop($arguments);	
		
		$filename = self::$links[array_shift($arguments)];
		if (empty($filename)) {
			return;
		}
		
		if (!is_writable($filename)) {
			Error::warning("Недостаточно прав для записи конфига в $filename");
			return;
		}		
		
		$file = file_get_contents($filename);
		if (empty($file)) {
			return;
		}		
		
		if (count($arguments) == 1) {
			$setting = array_shift($arguments);
			$section = '';
		} else {
			$section = array_shift($arguments);
			$setting = implode('.', $arguments);
		}
		
		if (!empty($section)) {
			$section = '.*?\n\['.preg_quote($section, '/').'\]';
		}
		
		$setting = preg_quote($setting, '/');
		
		$file = preg_replace('/^('.$section.'.*?\n'.$setting.'\s*=)[^\n]+/s', '$1 '.$value, $file);

		
		file_put_contents($filename, $file);
	}
	
	public static function __callStatic($name, $arguments) {

		if (!isset(self::$data[$name]) && isset(self::$links[$name])) {
			self::load(self::$links[$name]);
		}
		
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
	
	// Доступ на чтение, но не на запись
	
	public static function data() {

		return self::$data;
	} 
}
