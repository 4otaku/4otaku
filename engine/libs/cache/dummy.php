<?
	
class Cache_Dummy implements Cache_Interface_Single, Cache_Interface_Array, Plugins
{	
	public $able_to_work = true;
	
	public static function set ($key, $value, $expire) {}
	
	public static function set_array ($keys, $values, $expire) {}
	
	public static function get ($key) {
		return null;
	}
	
	public static function get_array ($keys) {
		return array_fill_keys($keys, null);
	}
	
	public static function delete ($key) {}
	
	public static function delete_array ($keys) {}
	
	public static function increment ($key, $value) {}
	
	public static function increment_array ($keys, $value) {}
	
	public static function decrement ($key, $value) {}
	
	public static function decrement_array ($keys, $value) {}
}
