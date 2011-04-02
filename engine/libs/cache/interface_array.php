<?
	
interface Cache_Interface_Array
{		
	public static function set_array ($keys, $values, $expire);
	
	public static function get_array ($keys);
	
	public static function delete_array ($keys);
	
	public static function increment_array ($keys, $value);
	
	public static function decrement_array ($keys, $value);
}
