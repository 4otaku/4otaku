<?

class Database
{
	// Хранит выполняющий операции объект.
	protected static $worker;
	
	protected static function init() {
		if (!is_object(self::$worker)) {
			$config = Config::database();
			var_dump($config);
		}
		
		return self::$worker;
	}
	
	public static function get_field() {
		self::init();
	}
}
