<?

class Database implements Plugins
{
	// Хранилище для объектов баз данных
	protected static $bases = array();
	
	// Обращается к объекту базы данных
	public static function db ($name = 'main') {
		if (empty(self::$bases[$name])) {
			self::$bases[$name] = self::init_db($name);
		}
		
		return self::$bases[$name];
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
	
	protected static function use_main_db() {
		$arguments = func_get_args();
		
		if (empty($arguments)) {
			return;
		}
		
		$function = array_shift($arguments);
		
		$worker = self::db('main');
		
		return call_user_func_array(array($worker, $function), $arguments);
	}
	
	// Алиасы для удобного обращения к главной базе данных
	
	public static function sql ($query, $params = array()) {
		return self::use_main_db('sql', $query, $params);
	}

	public static function get_table ($table, $values = '*', $condition = false, $params = false) {
		return self::use_main_db('get_table', $table, $values, $condition, $params);
	}
	
	public static function get_vector ($table, $values = '*', $condition = false, $params = false, $unset = true) {
		return self::use_main_db('get_vector', $table, $values, $condition, $params, $unset);
	}	
	
	public static function get_row ($table, $values = '*', $condition = false, $params = false) {
		return self::use_main_db('get_row', $table, $values, $condition, $params);
	}
	
	public static function get_field ($table, $value, $condition, $params = false) {
		return self::use_main_db('get_field', $table, $value, $condition, $params);
	}
	
	public static function insert ($table, $values) {
		return self::use_main_db('insert', $table, $values);
	}
	
	public static function replace ($table, $values, $dont_update = false) {
		return self::use_main_db('replace', $table, $values, $dont_update);
	}
	
	public static function bulk_insert ($table, $rows, $keys = false) {
		return self::use_main_db('bulk_insert', $table, $rows, $keys);
	}
	
	public static function update ($table, $condition, $values, $condition_params = array()) {
		return self::use_main_db('update', $table, $condition, $values, $condition_params);
	}	
	
	public static function delete ($table, $condition = false, $params = false) {
		return self::use_main_db('delete', $table, $condition, $params);
	}
	
	public static function last_id () {
		return self::use_main_db('last_id');
	}
	
	public static function debug ($print = true) {
		return self::use_main_db('debug', $print);
	}
	
	public static function make_search_condition ($field, $search_values) {
		return self::use_main_db('make_search_condition', $field, $search_values);
	}
	
	public static function free_result () {
		return self::use_main_db('free_result');
	}
	
	public static function begin () {
		return self::use_main_db('begin');
	}
	
	public static function commit () {
		return self::use_main_db('commit');
	}
	
	public static function rollback () {
		return self::use_main_db('rollback');
	}
	
	public static function conditional_insert ($table, $values, $keys = false, $deny_condition = false, $deny_params = array()) {
		return self::use_main_db('conditional_insert', $table, $values, $keys, $deny_condition, $deny_params);
	}
	
	public static function array_in ($field, $array) {
		return self::use_main_db('array_in', $field, $array);
	}
	
	public static function date_to_unix ($date) {
		return self::use_main_db('date_to_unix', $date);
	}
	
	public static function unix_to_date ($time = false) {
		return self::use_main_db('unix_to_date', $time);
	}
	
	public static function get_full_table ($table, $condition = false, $params = false) {
		return self::use_main_db('get_full_table', $table, $condition, $params);
	}
	
	public static function get_full_vector ($table, $condition = false, $params = false) {
		return self::use_main_db('get_full_vector', $table, $condition, $params);
	}	
	
	public static function get_full_row ($table, $condition = false, $params = false) {
		return self::use_main_db('get_full_row', $table, $condition, $params);
	}	
	
	public static function get_count ($table, $condition = false, $params = false) {
		return self::use_main_db('get_count', $table, $condition, $params);
	}	
	
	// И на всякий случай __callStatic	
	
	public static function __callStatic ($name, $arguments) {
		array_unshift($arguments, $name);

		return call_user_func_array(array('self','use_main_db'), $arguments);
	}	
}
