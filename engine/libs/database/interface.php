<?

Interface Database_Interface
{
	public function __construct($config);
	
	// Неопределенный общий запрос. 
	// По хорошему использоваться не должен, но возможность оставить надо.
	
	public function sql($query);
	
	// Запросы на чтение данных
	
	public function get_table($table, $values, $condition);
	
	public function get_vector($table, $values, $condition);
	
	public function get_row($table, $values, $condition);
	
	public function get_field($table, $value, $condition);
	
	// запросы на запись/модификацию
	
	public function insert($table, $values);
	
	public function replace($table, $values);
	
	public function bulk_insert($table, $rows);
	
	public function update($table, $condition, $values);
	
	public function delete($table, $condition);
	
	// Утилиты
	
	public function last_id();
	
	public function debug();
	
	public function set_counter();
	
	public function get_counter();
	
	public function make_search_condition($field, $search_values);
	
	// Транзакции
	
	public function begin();
	
	public function commit();
	
	public function rollback();
}
