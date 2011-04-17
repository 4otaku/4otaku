<?
	
class Cookie
{	
	public static function get_preferences($cookie) {
		if (empty($cookie)) {
			self::create_cookie();
			return array();			
		}
		
		self::set_cookie($cookie);
		
		$data = Database::get_vector('cookie', array('section', 'data'), '`cookie` = ?', $cookie);
		$info = Database::get_full_row('user', '`cookie` = ?', $cookie);

		if (empty($data) && empty($info)) {
			return array();
		}
		
		$data = (array) $data;
		
		foreach ($data as & $section) {
			$section = Crypt::unpack($section);
		}
		
		$data['info'] = $info;

		return $data;
	}
	
	public static function save_preference($cookie, $section, $key, $value) {		
		$condition = '`cookie` = ? and `section` = ?';
		
		$data = Database::get_field('cookie', 'data', $condition, array($cookie, $section));
		
		if (!empty($data)) {
			$data = Crypt::unpack($data);
			$need_expiration = false;
		} else {
			$data = array();
			$need_expiration = true;
		}
		
		$parts = explode('.', $key);
		$link = & $data;
		while ($part = array_shift($parts)) {
			$link = & $link[$part];
		}
		
		$link = $value;
		
		$insert = array(
			'cookie' => $cookie, 
			'section' => $section, 
			'data' => Crypt::pack($data),
		);
		
		if ($need_expiration) {
			$expires = Transform_String::parse_time(Config::main('cookie', 'lifespan'));		
			$insert['expires'] = Database::unix_to_date($expires);
		}
		
		Database::replace('cookie', $insert, array('cookie', 'section'));
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
