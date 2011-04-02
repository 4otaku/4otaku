<?

abstract class Database_Common
{
	// Хранит соединение с БД	
	protected $connection;
	
	// Последний результат запроса к БД
	protected $result;
	
	// Последний запрос
	protected $last_query;
	
	// Находимся ли мы в состоянии транзакции
	protected $transaction = false;
	
	// Префикс перед табличками
	protected $prefix = '';	
	
	public function conditional_insert($table, $values, $keys = false, $deny_condition = false, $deny_params = array()) {
		if ($this->get_row($table, $deny_condition, '*', $deny_params)) {
			return 0;
		}
		
		return $this->insert($table, $values, $keys);
	}
	
	public function array_in($field, $array) {
		if (!empty($array) && is_array($array)) {
			return "$field in (".str_repeat('?,',count($array)-1)."?)";
		} else {
//			Error::warning("Пустой массив при обращении к БД");
			return " and 0";
		}
	}
	
	public function format_insert_values (& $values) { 
		if (empty($values)) {
			Error::warning("Пустой массив для вставки в БД");
			return;
		}
		
		$values = (array) $values;
		
		$keys = array_keys($values);
		$values = array_values($values);
		
		if (count(array_diff_key($values,array_keys($keys))) === 0) {
			return "VALUES(NULL".str_repeat(",?",count($values)).")";
		}
		
		foreach ($keys as &$key) $key = '`'.trim($key,'`').'`';
		return "(".implode(",",$keys).") VALUES(?".str_repeat(",?",count($values) - 1).")";
	}
	
	public function date_to_unix($date) {
		return strtotime($date);
	}
	
	public function unix_to_date($time = false) {
		if (empty($time)) {
			$time = time();
		}
		
		return date("Y-m-d H:i:s", $time);
	}
	
	public function get_full_table($table, $condition = false, $params = false) {
		return $this->get_table($table, '*', $condition, $params);
	}
	
	public function get_full_vector($table, $condition = false, $params = false) {
		return $this->get_vector($table, '*', $condition, $params);
	}	
	
	public function get_full_row($table, $condition = false, $params = false) {
		return $this->get_row($table, '*', $condition, $params);
	}	
	
	public function get_count($table, $condition = false, $params = false) {
		return $this->get_field($table, 'count(*)', $condition, $params);
	}
}
