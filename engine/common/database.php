<?

class Database
{
	// Хранит выполняющий операции объект.
	protected static $worker;
	
	protected static function init() {
		if (!is_object(self::$worker)) {
			$config = Config::database();
			
			$class = 'Database_'.$config['Using'];
			$config = $config[$config['Using']];
			
			self::$worker = new $class($config);
		}
		
		return self::$worker;
	}
	
	public static function get_field($table, $value, $condition, $params = false) {
		$db = self::init();
		
		$return = $db->get_field($table, $value, $condition, $params);
		var_dump($return);
		$db->clear_results();
		
		return $return;
	}
}
