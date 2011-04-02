<?
	
interface Cache_Interface_Single
{	
	public static function set ($key, $value, $expire); 
	
	public static function get ($key);
	
	public static function delete ($key);
	
	public static function increment ($key, $value);
	
	public static function decrement ($key, $value);
}
