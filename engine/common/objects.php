<?

class Objects
{
	// Хранилище для объектов баз данных
	protected static $bases = array();
	
	// Обращается к объекту базы данных
	public static function db($name = 'main') {
		if (empty(self::$bases[$name])) {
			self::$bases[$name] = self::init_db($name);
		}
		
		return self::$bases[$name];
	}
	
	// Создает еще не созданный объект БД
	protected static function init_db($name) {
		$config = Config::database($name);
		
		if (empty($config) || empty($config['Type'])) {
			Error::fatal("Конфиг для базы данных $name не найден, либо поврежден.");
		}
		
		$class = 'Database_'.$config['Type'];
		
		if (!class_exists($class)) {
			Error::fatal("Класс для работы с СуБД {$config['Type']} отсутствует");
		}
		
		$return = new $class($config);
		
		if (!($return instanceOf Database_Interface)) {
			Error::fatal("Класс $class не использует стандартный интерфейс Database_Interface");
		}

		return $return;
	}	
}
