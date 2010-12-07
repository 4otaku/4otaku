<?

class sets extends def
{
	static function get($name, $key, $def_retrieve = true) {
		if ($key === false) {
			return self::array_get($name);
		}		
		
		if (isset(self::$data[$name][$key])) {
			return self::$data[$name][$key];
		} 
		
		// Если внезапно нужной настройки не оказалось, последние меры умирающего.
		if (empty(self::$data)) {
			include ROOT_DIR.SL.'engine'.SL.'config.php';
			self::import($sets);
			if (isset($sets[$name][$key])) {
				return $sets[$name][$key];
			}			
		} 
		
		if ($def_retrieve && ($def = def::get($name,$key,false))) {
			return $def;			
		} 

		return null;			
	}
}
