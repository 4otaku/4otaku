<?
	
class Cache implements Cache_Interface, Plugins
{	
	// Список классов которые можно использовать 
	// в роли кеширующих, в порядке приоритета
	
	public static $drivers_list = array(
		'Cache_Memcached',
		'Cache_Memcache',
		'Cache_Database',
		'Cache_Dummy',
	);
	
	// Для хранения объекта выполняющего собственно кеширование
	protected static $worker;
	
	protected static function get_worker () {
		if (is_object(self::$worker)) {
			return self::$worker;
		}
			
		$defined_driver = Config::main('cache', 'engine');
		
		if (!empty($defined_driver)) {
			$defined_driver{0} = strtoupper($defined_driver{0});
			$defined_driver = 'Cache_'.$defined_driver;

			if (class_exists($predefined_driver)) {
				self::$worker = new $predefined_driver();
				
				if (self::$worker instanceOf Cache_Interface) {
					return self::$worker;
				} else {
					unset(self::$worker);
				}
			}
		}
		
		foreach (self::$drivers_list as $driver) {
			if (class_exists($driver)) {
				self::$worker = new $driver();
				
				if (self::$worker instanceOf Cache_Interface) {
					break;
				} else {
					unset(self::$worker);
				}
			}
		}
		
		if (empty(self::$worker)) {
			Error::fatal("Не найден подоходящий класс для кеширования");
		}
		
		return self::$worker;
	}
	
	public static function set ($key, $value, $expire = null) {
		
	}
	
	public static function set_array ($keys, $values, $expire = null) {
		
	}
	
	public static function get ($key) {
		
	}
	
	public static function get_array ($keys) {
		
	}	
	
	public static function delete ($key) {
		
	}
	
	public static function delete_array ($keys) {
		
	}
	
	public static function increment ($key, $value = 1) {
		
	}
	
	public static function increment_array ($keys, $values = 1) {
		
	}
	
	public static function decrement ($key, $value = 1) {
		
	}
	
	public static function decrement_array ($keys, $values = 1) {
		
	}
}
