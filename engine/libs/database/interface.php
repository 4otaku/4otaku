<?

Interface Database_Interface
{
	public function __construct($config);
	
	// Неопределенный общий запрос. 
	// По хорошему использоваться не должен, но возможность оставить надо.
	
	public function sql($query);
	
	// Запросы на чтение данных
	
	public function get_table($table, $condition, $values);
	
	public function get_vector($table, $condition, $values, $unset);
	
	public function get_row($table, $condition, $values);
	
	public function get_field($table, $condition, $value);
	
	// запросы на запись/модификацию
	
	public function insert($table, $values);
	
	public function bulk_insert($table, $rows);
	
	public function update($table, $condition, $fields, $values);
	
	public function delete($table, $condition);
	
	// Утилиты
	
	public function last_id();
	
	public function debug();
	
	// Транзакции
	
	public function begin();
	
	public function commit();
	
	public function rollback();
}
