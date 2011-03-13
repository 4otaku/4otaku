<?

class Objects
{
	// Хранилище для объектов баз данных
	protected static $bases = array();
	
	// Хранилище для объектов выполняющих преобразования
	protected static $transformers = array();	
	
	// Для контроллера запроса
	public static $controller = false;	
	
	// Обращается к объекту базы данных
	public static function db ($name = 'main') {
		if (empty(self::$bases[$name])) {
			self::$bases[$name] = self::init_db($name);
		}
		
		return self::$bases[$name];
	}
	
	// Обращается к объекту базы данных
	public static function transform ($name) {
		if (empty($name)) {
			return false;
		}
		
		if (empty(self::$transformers[$name])) {
			self::$transformers[$name] = self::init_transformer($name);
		}
		
		return self::$transformers[$name];
	}	
	
	// Создает еще не созданный объект БД
	protected static function init_db ($name) {
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
		
		if (!($return instanceOf Database_Common)) {
			Error::fatal("Класс $class не наследует от общего класса Database_Common");
		}		

		return $return;
	}	
	
	// Создает еще не созданный объект преобразователя
	protected static function init_transformer ($name) {
		$name{0} = strtoupper($name{0});
		
		$class = 'Transform_'.$name;
		
		if (!class_exists($class)) {
			Error::fatal("Класс для преобразований {$name} отсутствует");
		}	

		return new $class();
	}	
}
