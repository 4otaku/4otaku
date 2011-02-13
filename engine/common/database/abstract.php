<?

Interface Database_Interface
{
	public abstract function __construct($config);
	
	public abstract function sql($query);
	
	public abstract function get_table($table, $values, $condition = false);
	
	public abstract function get_vector($table, $values, $condition = false);
	
	public abstract function get_row($table, $values, $condition);
	
	public abstract function get_field($table, $value, $condition);
	
	public abstract function insert($table, $values, $keys = false);
	
	public abstract function bulk_insert();
	
	public abstract function update();
	
	public abstract function update_field();	
	
	public abstract function delete($table, $condition);
	
	public abstract function last_id();
	
	public abstract function debug();
}
