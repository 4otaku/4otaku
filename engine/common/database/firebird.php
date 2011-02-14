<?

class Database_Firebird implements Database_Interface
{
	// Хранит соединение с БД	
	private $connection;
	
	// Последний результат запроса к БД
	private $result;
	
	// Последний запрос
	private $last_query;
	
	public function __construct($config) {
		
	}
	
	protected function query($query, $params = array()) {
		
	}
	
	public function sql($query, $params = array()) {
		
	}	
	
	protected function get_common($table, $condition = false, $values = '*', $params = false) {

	}

	public function get_table($table, $condition = false, $values = '*', $params = false) {

	}
	
	public function get_vector($table, $key, $condition = false, $values = '*', $params = false) {

	}	
	
	public function get_row($table, $values, $condition, $params = false) {

	}
	
	public function get_field($table, $value, $condition, $params = false) {

	}
	
	public function insert($table, $values, $keys = false) {

	}
	
	public function bulk_insert($table, $rows, $keys = false) {
	
	}
	
	public function update($table, $condition, $fields, $values = false) {

	}	
	
	public function delete($table, $condition = false) {

	}
	
	public function last_id() {

	}
	
	public function debug($print = true) {

	}
	
	public function free_result() {

	}
	
	public function begin() {
		
	}
	
	public function commit() {

	}
	
	public function rollback() {

	}
}
