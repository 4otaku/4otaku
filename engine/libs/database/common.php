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
}
