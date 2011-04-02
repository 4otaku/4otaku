<?
	
class Cache_Dummy implements Cache_Interface, Plugins
{	
	public static function set ($key, $value, $expire = null) {
		return false;
	}
	
	public static function set_array ($keys, $values, $expire = null) {
		return false;
	}
	
	public static function get ($key) {
		return null;
	}
	
	public static function get_array ($keys) {
		return array_fill_keys($keys, null);
	}
	
	public static function delete ($key) {
		return true;
	}
	
	public static function delete_array ($keys) {
		return true;		
	}
	
	public static function increment ($key, $value = 1) {
		return false;		
	}
	
	public static function increment_array ($keys, $values = 1) {
		return false;		
	}
	
	public static function decrement ($key, $value = 1) {
		return false;		
	}
	
	public static function decrement_array ($keys, $values = 1) {
		return false;		
	}
}
