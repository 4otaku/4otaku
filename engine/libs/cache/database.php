<?
	
class Cache_Database implements Cache_Interface_Single, Cache_Interface_Array, Plugins
{	
	public static function set ($key, $value, $expire = null) {
		
		if (is_array($value)) {
			$value = Crypt::pack($value);
		}
		
		if (empty($expire)) {
			$expire = DAY;
		}
		
		$expire = Objects::db()->unix_to_date(time() + $expire);
		
		$insert = array(
			'key' => $key,
			'value' => $value,
			'expires' => $expire
		);
		
		Objects::db()->replace('cache', $insert, 'key');
	}
	
	public static function set_array ($keys, $values, $expire = null) {
		
		if (empty($expire)) {
			$expire = DAY;
		}
				
		$expire = Objects::db()->unix_to_date(time() + $expire);
			
		$values = array_combine($keys, $values);
				
		foreach ($values as $key => $value) {
			self::set($key, $value, $expire);
		}
	}
	
	public static function get ($key) {
		
		$value = Objects::db->get_field('cache', 'value', '`key` = ? and `expires` > NOW()', $key);
		
		return Crypt::unpack($value);
	}
	
	public static function get_array ($keys) {
		
		$condition = Objects::db()->array_in('key', $keys);
		$condition = $condition.' and `expires` > NOW()';	
		
		$values = Objects::db->get_vector('cache', 'key,value', $condition, $keys);
		
		foreach ($values as & $value) {
			$value = Crypt::unpack($value);
		}
		
		return $values;
	}
	
	public static function delete ($key) {
		
		Objects::db->delete('cache', '`key` = ?', $key);
	}
	
	public static function delete_array ($keys) {
		
		$condition = Objects::db()->array_in('key', $keys);
		Objects::db->delete('cache', $condition, $keys);
	}
	
	public static function increment ($key, $value = 1) {
		
		$sql = 'update <pr>cache set `value` = `value` + ? where `key` = ?';
		
		Objects::db->sql($sql, array($value, $key));
	}
	
	public static function increment_array ($keys, $value = 1) {
		
		$sql = 'update <pr>cache set `value` = `value` + ? where';
		$sql .= Objects::db()->array_in('key', $keys);
		
		if (is_array($value)) {
			$value = current($value);
		}
		
		Objects::db->sql($sql, array($value, $key));		
	}
	
	public static function decrement ($key, $value = 1) {
		
		$sql = 'update <pr>cache set `value` = `value` - ? where `key` = ?';
		
		Objects::db->sql($sql, array($value, $key));		
	}
	
	public static function decrement_array ($keys, $value = 1) {
		
		$sql = 'update <pr>cache set `value` = `value` - ? where';
		$sql .= Objects::db()->array_in('key', $keys);
		
		if (is_array($value)) {
			$value = current($value);
		}
		
		Objects::db->sql($sql, array($value, $key));			
	}
}
