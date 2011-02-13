<?

Interface Database_Interface
{
	public function __construct($config);
	
	public function sql($query);
	
	public function get_table($table, $values, $condition = false);
	
	public function get_vector($table, $values, $condition = false);
	
	public function get_row($table, $values, $condition);
	
	public function get_field($table, $value, $condition);
	
	public function insert($table, $values);
	
	public function bulk_insert($table, $rows);
	
	public function update($table, $condition, $fields, $values);
	
	public function delete($table, $condition);
	
	public function last_id();
	
	public function debug();
}
