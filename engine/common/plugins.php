<?

final class Plugins
{
	// Хранилище для загруженных плагинов
	public static $instances = array();
	
	// Для рабочего объекта
	private static $worker;
	
	public static function load($file) {
		if (
			!is_readable($file) || 
			pathinfo($file, PATHINFO_EXTENSION) !== 'php' ||
			!self::test($file)
		) {
			return;
		}
		
		$class_vars = null;
		$class_name = 'Plugin_'.pathinfo($file, PATHINFO_FILENAME);
		
		include_once($file);		
				
		self::$instances[] = new $class_name($class_vars);
		
		return true;
	}
	
	private static function test($file) {
		// TODO: придумать формат для плагинов и написать проверку
		return true;
	}
	
	public function __call($name, $arguments) {

	} 
	
	public static function extend() {
		if (!is_object(self::$worker)) {
			self::init();
		}
		
		return self::$worker;
	} 
	
	public static function init() {
		self::$worker = new Plugins();
	} 
}
