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
		return "$field in (".str_repeat('?,',count($array)-1)."?)";
	}
	
	public function date_to_unix($date) {
		return strtotime($date);
	}
	
	public function unix_to_date($time) {
		return date("Y-m-d H:i:s", $time);
	}	
}
