<?

class obj
{
	protected static $data = array();

	public static function db ($base = 'main') {
		if (empty(self::$data['db'])) {
			self::$data['db'] = new mysql();
		}

		self::$data['db']->set_connection($base);
		return self::$data['db'];
	}

	public static function transform ($type) {
		if (empty(self::$data['transform'][$type])) {
			$class = 'transform__' . $type;
			self::$data['transform'][$type] = new $class();
		}

		return self::$data['transform'][$type];
	}

	public static function get ($name) {
		if (empty(self::$data[$name])) {
			self::$data[$name] = new $name();
		}

		return self::$data[$name];
	}
}
