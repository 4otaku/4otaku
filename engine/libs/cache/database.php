<?
	
class Cache_Database implements Cache_Interface_Single, Cache_Interface_Array, Plugins
{
	public $able_to_work = true;	
	
	public function __construct ($config) {
		$scheme = Database::sql('DESCRIBE `<pr>cache`');
		
		if (empty($scheme)) {
			$this->able_to_work = false;
			return;
		}
		
		$fields = array();
		$required_fields = array('key',	'value', 'expires');
		
		foreach ($scheme as $row) {
			$fields[] = $row['Field'];
		}		
				
		$this->able_to_work = ! (bool) array_diff($required_fields, $fields);
	}
	
	public static function set ($key, $value, $expire = null) {
		
		if (is_array($value)) {
			$value = Crypt::pack($value);
		}
		
		if (empty($expire)) {
			$expire = DAY;
		}
		
		$expire = Database::unix_to_date(time() + $expire);
		
		$insert = array(
			'key' => $key,
			'value' => $value,
			'expires' => $expire
		);
		
		Database::replace('cache', $insert, 'key');
	}
	
	public static function set_array ($keys, $values, $expire = null) {
		
		if (empty($expire)) {
			$expire = DAY;
		}
				
		$expire = Database::unix_to_date(time() + $expire);
			
		$values = array_combine($keys, $values);
				
		foreach ($values as $key => $value) {
			self::set($key, $value, $expire);
		}
	}
	
	public static function get ($key) {
		
		$value = Database::get_field('cache', 'value', '`key` = ? and `expires` > NOW()', $key);
		
		return Crypt::unpack($value);
	}
	
	public static function get_array ($keys) {
		
		$condition = Database::array_in('key', $keys);
		$condition = $condition.' and `expires` > NOW()';	
		
		$values = Database::get_vector('cache', array('key','value'), $condition, $keys);

		foreach ($values as & $value) {
			$value = Crypt::unpack($value);
		}
		
		return $values;
	}
	
	public static function delete ($key) {
		
		Database::delete('cache', '`key` = ?', $key);
	}
	
	public static function delete_array ($keys) {
		
		$condition = Database::array_in('key', $keys);
		Database::delete('cache', $condition, $keys);
	}
	
	public static function increment ($key, $value = 1) {
		
		$sql = 'update <pr>cache set `value` = `value` + ? where `key` = ?';
		
		Database::sql($sql, array($value, $key));
	}
	
	public static function increment_array ($keys, $value = 1) {
		
		$sql = 'update <pr>cache set `value` = `value` + ? where';
		$sql .= Database::array_in('key', $keys);
		
		if (is_array($value)) {
			$value = current($value);
		}
		
		Database::sql($sql, array($value, $key));		
	}
	
	public static function decrement ($key, $value = 1) {
		
		$sql = 'update <pr>cache set `value` = `value` - ? where `key` = ?';
		
		Database::sql($sql, array($value, $key));		
	}
	
	public static function decrement_array ($keys, $value = 1) {
		
		$sql = 'update <pr>cache set `value` = `value` - ? where';
		$sql .= Database::array_in('key', $keys);
		
		if (is_array($value)) {
			$value = current($value);
		}
		
		Database::sql($sql, array($value, $key));			
	}
}
