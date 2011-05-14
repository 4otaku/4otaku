<?
	
class Cookie
{	
	public static function get_preferences($cookie) {
		if (empty($cookie)) {
			self::create_cookie();
			return array();			
		}
		
		self::set_cookie($cookie);
		
		$data = Database::get_vector('session', array('key', 'value', 'updated'), '`cookie` = ?', $cookie);
		$info = Database::get_full_row('user', '`cookie` = ?', $cookie);
		
		$return = array('updated' => '');
		
		foreach ((array) $data as $key => $setting) {
			$key = explode('.', $key);
			
			$pointer = & $return;
			
			while ($part = array_shift($key)) {
				$pointer = & $pointer[$part];
			}
			
			$pointer = Crypt::unpack($setting['value']);
			
			$return['updated'] = max($return['updated'], $setting['updated']);
		}

		$return['info'] = (array) $info;

		return $return;
	}
	
	public static function save_preference($cookie, $key, $value) {			
		if (is_array($value) || is_object($value)) {
			$value = Crypt::pack($value);
		}
		
		$insert = array(
			'cookie' => $cookie, 
			'key' => $key, 
			'value' => $value,
		);		
		
		Database::replace('session', $insert, array('cookie', 'key'));
	}
	
	public static function set_cookie ($cookie) {		
		$cookie_domain = Config::main('cookie', 'domain') ?
			Config::main('cookie', 'domain') :
			$_SERVER['SERVER_NAME'];   
		
		if ($cookie_domain == 'localhost') {
			$cookie_domain = '';
		}
		
		$expires = Transform_String::parse_time(Config::main('cookie', 'lifespan'));
		$update = array('expires' => Database::unix_to_date($expires));
		
		Database::update('cookie', '`cookie` = ?', $update, $cookie);
		
		setcookie(
			Config::main('cookie', 'name'), 
			$cookie, 
			Transform_String::parse_time(Config::main('cookie', 'lifespan')), 
			'/', 
			$cookie_domain
		);		
	}
	
	protected static function create_cookie () {
		$cookie = Crypt::md5_salt(
			time() . Globals::$user_data['ip'], 
			strrev(Config::main('salt'))
		);	
		
		self::set_cookie($cookie);
		
		Globals::$user_data['cookie'] = $cookie;	
	}
}
