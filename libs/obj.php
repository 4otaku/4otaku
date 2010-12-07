<?

class obj
{
	static $data = array();
	
	static function db($base = 'main') {
		if (empty(self::$data['db'])) {
			self::$data['db'] = new mysql();
		}
		
		self::$data['db']->set_connection($base);
		return self::$data['db'];
	}
}
