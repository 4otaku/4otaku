<?

class sets
{
	static $data = array();

	static function import($def) {
		foreach ($def as $key => $value) {
			self::$data[$key] = $value;
		}
	}

	static function array_get($name) {
		if (isset(self::$data[$name])) {
			return self::$data[$name];
		}

		return array();
	}

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

	static function set($name, $key, $value) {
		self::$data[$name][$key] = $value;
	}

	public static function __callStatic($name, $arguments) {
		return self::get($name,$arguments[0]);
	}

	static function site($key) {
		return self::get('site',$key);
	}

	static function video($key) {
		return self::get('video',$key);
	}

	static function user($key) {
		return self::get('user',$key);
	}

	static function board($key) {
		return self::get('board',$key);
	}

	static function pp($key) {
		return self::get('pp',$key);
	}

	static function dir($key) {
		return self::get('dir',$key);
	}

	static function edit($key) {
		return self::get('edit',$key);
	}
}
