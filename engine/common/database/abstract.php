<?

abstract class Database_Abstract
{
	public abstract function connect($settings);
	
	public abstract function query($query);
	
	public abstract function get($table, $values, $condition = false);
	
	public abstract function get_row($table, $values, $condition);
	
	public abstract function get_field($table, $value, $condition);
	
	public abstract function insert($table, $values, $keys = false);
	
	public abstract function bulk_insert();
	
	public abstract function update();
	
	public abstract function update_field();	
	
	public abstract function delete($table, $condition);
	
	public abstract function get_last_id($table);
	
	public abstract function clear_results();
}
